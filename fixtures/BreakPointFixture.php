<?php

declare(strict_types=1);

namespace App\FeeCalculator\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\FeeCalculator\Infrastructure\Entity\BreakPoint;

class BreakPointFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $manager->persist(
                new BreakPoint(
                    10000 + 2000 * $i,
                    0.05 * $i + 1,
                )
            );
        }

        $manager->flush();
    }
}
