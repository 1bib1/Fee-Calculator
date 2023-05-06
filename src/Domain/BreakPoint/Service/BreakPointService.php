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

    public function getBreakPoints(int $term): array
    {
        // There should be some actual logic that would get relevant break points
        // from database, for specific term
        // for now, just return all of them
        return $this->breakPointRepository->findAll() ?? [];
    }
}
