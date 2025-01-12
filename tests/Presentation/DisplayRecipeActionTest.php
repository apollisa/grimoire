<?php

namespace App\Tests\Presentation;

use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Servings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class DisplayRecipeActionTest extends WebTestCase
{
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->request("GET", "/recettes/1");
    }

    public function testDisplayRecipe(): void
    {
        self::assertSelectorTextContains("h1", "Parmentier");
    }

    public function testDisplayRecipeIngredients(): void
    {
        self::assertSelectorTextContains("li", "1 kg patates");
    }

    public function testDisplayRecipeInstructions(): void
    {
        self::assertSelectorTextContains("ol li", "Préparer une purée");
    }

    public function testDisplayRecipeServings(): void
    {
        self::assertSelectorTextContains("p", "4 parts");
    }

    public function testDisplayRecipeFolder(): void
    {
        self::assertAnySelectorTextContains("main p", "Plats");
    }

    public function testDoesNotDisplayEmptyInstructions(): void
    {
        $container = self::getContainer();
        $folder = $container
            ->get(FolderRepository::class)
            ->ofId(new FolderId(1));
        $manager = $container->get(EntityManagerInterface::class);
        $recipe = new Recipe($folder, "Pâtes", new Servings(2), null, [], []);
        $manager->persist($recipe);
        $manager->flush();

        $this->client->request("GET", "/recettes/{$recipe->id()}");

        self::assertAnySelectorTextNotContains("h2", "Instructions");
    }
}
