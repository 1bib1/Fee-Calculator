<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Fee\Interface;

use App\FeeCalculator\Domain\Fee\ValueObject\LoanProposal;

interface FeeCalculatorInterface
{
    public function calculate(LoanProposal $application): float;
}
