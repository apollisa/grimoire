<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DisplayRecipeActionTest extends WebTestCase
{
    public function testDisplayRecipe(): void
    {
        $client = self::createClient();
        $client->request("GET", "/recettes/1");

        self::assertSelectorTextContains("h1", "Parmentier");
    }
}
