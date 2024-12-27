<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DisplayRecipeActionTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::createClient()->request("GET", "/recettes/1");
    }

    public function testDisplayRecipe(): void
    {
        self::assertSelectorTextContains("h1", "Parmentier");
    }

    public function testDisplayRecipeIngredients(): void
    {
        self::assertSelectorTextContains("li", "1 kg patates");
    }
}
