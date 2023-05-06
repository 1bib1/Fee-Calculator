<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\BreakPoint\Service;

use App\FeeCalculator\Domain\BreakPoint\Interface\BreakPointServiceInterface;
use App\FeeCalculator\Infrastructure\Repository\BreakPointRepository;

class BreakPointService implements BreakPointServiceInterface
{
    public function __construct(
        protected readonly BreakPointRepository $breakPointRepository,
    ) {}

    public function getBreakPoints(): array
    {
        return $this->breakPointRepository->findAll() ?? [];
    }
}
