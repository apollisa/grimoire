<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListRecipesActionTest extends WebTestCase
{
    public function testDisplaysRecipes(): void
    {
        $client = self::createClient();
        $client->request("GET", "/recettes");

        self::assertSelectorTextContains("a", "Parmentier");
    }
}
