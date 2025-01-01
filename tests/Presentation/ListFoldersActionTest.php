<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListFoldersActionTest extends WebTestCase
{
    protected function setUp(): void
    {
        $client = self::createClient();
        $client->request("GET", "/dossiers");
    }

    public function testDisplaysAllRecipeLink(): void
    {
        self::assertSelectorTextContains("a", "Toutes vos recettes");
    }

    public function testDisplaysFolder(): void
    {
        self::assertSelectorTextContains("a ~ a", "Plats");
    }
}
