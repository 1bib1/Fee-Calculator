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

        /** @var BreakPoint $breakPoint */
        foreach ($breakPoints as $breakPoint) {
            // if value exactly matches break point, just count it now
            if ($breakPoint->getAmount() === $application->amount) {
                return $application->amount * $breakPoint->getFee();
            }
        }

        return 0.0;
    }

    private function getBreakPoints(): array
    {
        $breakPoints = $this->breakPointService->getBreakPoints();

        if (count($breakPoints) === 0) {
            throw new Exception('No break point found');
        }

        return $breakPoints;
    }
}
