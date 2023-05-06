<?php

declare(strict_types=1);

namespace App\FeeCalculator\Aggregate\Fee\Calculator;

use App\FeeCalculator\Domain\BreakPoint\Interface\BreakPointServiceInterface;
use App\FeeCalculator\Domain\Fee\Interface\FeeCalculatorInterface;
use App\FeeCalculator\Domain\Fee\ValueObject\LoanProposal;
use App\FeeCalculator\Infrastructure\Entity\BreakPoint;
use Exception;
use InvalidArgumentException;
use function sprintf;

class FeeCalculator implements FeeCalculatorInterface
{
    public function __construct(
        protected readonly BreakPointServiceInterface $breakPointService,
    ) {}

    public function calculate(LoanProposal $application): float
    {
        $breakPoints = $this->getBreakPoints();
        $requestedLoanAmount = (float) $application->amount; // could be int

        /** @var BreakPoint $breakPoint */
        foreach ($breakPoints as $breakPoint) {
            // if value exactly matches break point, just count it now
            if ($breakPoint->getAmount() === $application->amount) {
                return $this->roundUpFee($requestedLoanAmount * $breakPoint->getFee());
            }
        }

        [$minBreakPoint, $maxBreakPoint] = $this->findBreakPoints($breakPoints, $requestedLoanAmount);
        $minFee = $minBreakPoint->getFee();

        // x-x0
        $deltaFee = $requestedLoanAmount - $minBreakPoint->getAmount();
        // divide by x1-x0
        $deltaFee = $deltaFee / ($maxBreakPoint->getAmount() - $minBreakPoint->getAmount());
        // multiply by y1-y0
        $deltaFee = $deltaFee * ($maxBreakPoint->getFee() - $minBreakPoint->getFee());

        return $this->roundUpFee($requestedLoanAmount * ($minFee + $deltaFee));
    }

    private function roundUpFee(float $fee): int
    {
        return (int) (ceil(round($fee / 5, 2)) * 5);
    }
    private function getBreakPoints(): array
    {
        $breakPoints = $this->breakPointService->getBreakPoints();

        if (count($breakPoints) === 0) {
            throw new Exception('No break point found');
        }

        return $breakPoints;
    }

    private function findBreakPoints(array $breakPoints, float $loanAmount): array
    {
        $minBreakpoint = $maxBreakpoint = null;

        /** @var BreakPoint $breakpoint */
        foreach ($breakPoints as $breakpoint)
        {
            $breakpointAmount = $breakpoint->getAmount();

            if (($breakpointAmount < $loanAmount) &&
                (true === empty($minBreakpoint) || ($minBreakpoint->getAmount() < $breakpointAmount)))
            {
                $minBreakpoint = $breakpoint;
            }

            if (($loanAmount < $breakpointAmount) &&
                (true === empty($maxBreakpoint) or ($breakpointAmount < $maxBreakpoint->getAmount())))
            {
                $maxBreakpoint = $breakpoint;
            }
        }

        if (($minBreakpoint === null) or ($maxBreakpoint === null)) { // should have separate checks
            throw new InvalidArgumentException(sprintf('No break point found for loan amount %s', $loanAmount));
        }

        return [$minBreakpoint, $maxBreakpoint];
    }
}
