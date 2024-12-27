<?php

namespace App\Tests\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class ClearDayActionTest extends WebTestCase
{
    use ClockSensitiveTrait;
    public function testClearsDay(): void
    {
        self::mockTime("2024-08-27");
        $client = self::createClient();

        $crawler = $client->request("GET", "/");
        $client->submit($crawler->filter("[aria-label='Retirer']")->form());

        $repository = self::getContainer()->get(MenuRepository::class);
        $monday = $repository->last()->day(DayOfWeek::MONDAY);
        self::assertEmpty($monday->meals());
    }
}
