<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class DisplayMenuActionTest extends WebTestCase
{
    use ClockSensitiveTrait;

    protected function setUp(): void
    {
        self::mockTime("2024-08-27");
        $client = self::createClient();
        $client->request("GET", "/");
    }

    public function testDisplaysToday(): void
    {
        self::assertSelectorTextContains("h2", "Mardi 27 août");
    }

    public function testDisplaysRecipe(): void
    {
        self::assertSelectorTextContains("p", "Parmentier");
    }

    public function testDisplaysRecipeLink(): void
    {
        self::assertSelectorExists("a[href='/recettes/1']");
    }
}
