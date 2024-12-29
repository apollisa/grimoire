<?php

namespace App\Tests\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlanMealActionTest extends WebTestCase
{
    public function testPlansMeal(): void
    {
        $client = self::createClient();
        $day = DayOfWeek::MONDAY;
        $client->request("GET", "/1/recettes", ["jour" => $day->value]);

        $client->submitForm("Ajouter");

        $container = self::getContainer();
        $menu = $container->get(MenuRepository::class)->last();
        self::assertCount(1, $menu->day($day)->meals());
    }
}
