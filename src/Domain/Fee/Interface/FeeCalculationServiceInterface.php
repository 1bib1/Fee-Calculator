<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Fee\Interface;

use Symfony\Component\HttpFoundation\Request;

interface FeeCalculationServiceInterface
{
    public function calculateFromRequest(Request $request): int;
}
