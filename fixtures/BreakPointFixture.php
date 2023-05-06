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
        for ($i = 0; $i < 20; $i++) {
            $manager->persist(
                new BreakPoint(
                    1000 * $i,
                    round((20 - $i) * 0.01, 2),
                )
            );
        }

        $manager->flush();
    }
}
