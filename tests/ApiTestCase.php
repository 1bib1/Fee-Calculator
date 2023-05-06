<?php

declare(strict_types=1);

namespace App\FeeCalculator\Tests;

use App\FeeCalculator\Fixtures\BreakPointFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->followRedirects();

        $databaseTool = self::getContainer()
            ->get(DatabaseToolCollection::class)->get();

        $databaseTool->loadFixtures([BreakpointFixture::class], false);

        self::bootKernel();
    }
}
