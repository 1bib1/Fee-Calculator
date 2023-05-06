<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\BreakPoint\Interface;

interface BreakPointServiceInterface
{
    public function getBreakPoints(int $term): array;
}
