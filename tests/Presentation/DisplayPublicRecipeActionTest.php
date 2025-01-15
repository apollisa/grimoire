<?php

namespace App\Tests\Presentation;

use App\Application\RecipeSharer;
use App\Domain\Recipe\RecipeId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class DisplayPublicRecipeActionTest extends WebTestCase
{
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $container = self::getContainer();
        $container->get(RecipeSharer::class)->share(new RecipeId(1));
        $container->get(EntityManagerInterface::class)->flush();
        $this->client->request("GET", "/public/recettes/parmentier");
    }

    public function testDisplaysRecipe(): void
    {
        self::assertSelectorTextContains("h1", "Parmentier");
    }

    public function testDoesNotDisplayNavbar(): void
    {
        self::assertSelectorNotExists("nav");
    }
}
