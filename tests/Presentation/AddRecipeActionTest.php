<?php

namespace App\Tests\Presentation;

use App\Domain\Recipe\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class AddRecipeActionTest extends WebTestCase
{
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient([], ["HTTP_X_STIMULUS" => "true"]);
        $this->client->request("GET", "/recettes/nouvelle");
    }

    public function testDisplaysAddButton(): void
    {
        $this->client->request("GET", "/recettes");

        self::assertSelectorExists("[aria-label='Ajouter une recette']");
    }

    public function testDisplaysTitle(): void
    {
        self::assertSelectorTextContains("h2", "Nouvelle recette");
    }

    public function testDisplaysFolderSelect(): void
    {
        self::assertSelectorTextContains("option", "Plats");
    }

    public function testDisplaysNameInput(): void
    {
        self::assertSelectorTextContains("input ~ label", "Nom");
    }

    public function testCreatesRecipe(): void
    {
        $this->createRecipe();

        $recipe = $this->getLastRecipe();
        self::assertResponseRedirects("/recettes/{$recipe->id()->value()}");
    }

    private function createRecipe(): void
    {
        $this->client->submitForm("Ajouter", [
            "recipe[name]" => "Pizza",
            "recipe[servings]" => 4,
        ]);
    }

    private function getLastRecipe(): Recipe
    {
        $manager = self::getContainer()->get(EntityManagerInterface::class);
        $repository = $manager->getRepository(Recipe::class);
        return $repository->findOneBy([], ["updatedAt" => "DESC"]);
    }

    public function testCreatesRecipeWithNoInstructions(): void
    {
        $this->createRecipe();

        self::assertEmpty($this->getLastRecipe()->instructions());
    }

    public function testDisplaysFolderSelectWithCurrentFolder(): void
    {
        $id = "2";
        $crawler = $this->client->request(
            "GET",
            "/recettes/nouvelle?dossier=$id",
        );

        $field = $crawler->selectButton("Ajouter")->form()["recipe[folder]"];
        self::assertEquals($id, $field->getValue());
    }
}
