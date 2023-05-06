<?php

declare(strict_types=1);

namespace App\FeeCalculator\Aggregate\Fee\Service;

use App\FeeCalculator\Domain\Fee\Interface\FeeCalculationServiceInterface;
use App\FeeCalculator\Domain\Fee\Interface\FeeCalculatorInterface;
use App\FeeCalculator\Domain\Fee\ValueObject\LoanProposal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeeCalculationService implements FeeCalculationServiceInterface
{
    public function __construct(
        protected readonly ValidatorInterface $validator,
        protected readonly FeeCalculatorInterface $calculator,
    ) {}

    public function calculateFromRequest(Request $request): int
    {
        $application = $this->createLoanProposalFromRequest($request);
        $this->validateLoanProposal($application);

        return (int) $this->calculator->calculate($application);
    }

    private function createLoanProposalFromRequest(Request $request): LoanProposal
    {
        $parameters = json_decode($request->getContent(), true);

        return new LoanProposal(
            $parameters['term'] ?? null,
            $parameters['amount'] ?? null,
        );
    }

    private function validateLoanProposal(LoanProposal $application): void
    {
        $violations = $this->validator->validate($application);

        if ($violations->count() > 0) {
            throw new ValidationFailedException(null, $violations);
        }
    }
}
