<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DetailFolderActionTest extends WebTestCase
{
    public function testDisplaysFolderRecipeLink(): void
    {
        $client = self::createClient();
        $client->request("GET", "/dossiers/1");
        self::assertSelectorTextContains("a", "Parmentier");
    }
}
