<?php

namespace App\Tests\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class PlanMealActionTest extends WebTestCase
{
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testPlansMeal(): void
    {
        $day = DayOfWeek::MONDAY;
        $this->client->request("GET", "/1/recettes", ["jour" => $day->value]);

        $this->client->submitForm("Ajouter");

        $container = self::getContainer();
        $menu = $container->get(MenuRepository::class)->last();
        self::assertCount(1, $menu->day($day)->meals());
    }

    public function testPlansRemains(): void
    {
        $day = DayOfWeek::WEDNESDAY;
        $this->client->request("GET", "/1/recettes", ["jour" => $day->value]);

        $this->client->submitForm("Ajouter");

        $container = self::getContainer();
        $menu = $container->get(MenuRepository::class)->last();
        self::assertCount(1, $menu->day($day)->meals());
    }
}
