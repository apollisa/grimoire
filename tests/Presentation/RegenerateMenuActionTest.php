<?php

namespace App\Tests\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegenerateMenuActionTest extends WebTestCase
{
    public function testRegeneratesLastMenu(): void
    {
        $client = self::createClient();

        $crawler = $client->request("GET", "/");
        $client->submit($crawler->filter("[aria-label='Regénérer']")->form());

        $repository = self::getContainer()->get(MenuRepository::class);
        $monday = $repository->last()->day(DayOfWeek::MONDAY);
        self::assertNotEmpty($monday->meals());
    }
}
