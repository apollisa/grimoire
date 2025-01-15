<?php

namespace App\Tests\Presentation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class ShareRecipeActionTest extends WebTestCase
{
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testSharesRecipe(): void
    {
        $crawler = $this->client->request("GET", "/recettes/1");

        $form = $crawler->filter("[aria-label='Partager']")->form();
        $this->client->submit($form);

        self::assertResponseRedirects("/public/recettes/parmentier");
    }
}
