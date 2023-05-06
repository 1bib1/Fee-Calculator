<?php

declare(strict_types=1);

namespace App\FeeCalculator\Infrastructure\Repository;

use App\FeeCalculator\Infrastructure\Entity\BreakPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BreakPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BreakPoint::class);
    }
}
