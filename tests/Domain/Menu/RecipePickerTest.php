<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\Menu;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\Month;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\DatePoint;

class RecipePickerTest extends KernelTestCase
{
    private RecipePicker $picker;
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $container = self::getContainer();
        $this->manager = $container->get(EntityManagerInterface::class);
        $this->addRecipe(new Seasonality(Month::JANUARY, Month::JANUARY));
        $this->addRecipe(new Seasonality(Month::JANUARY, Month::FEBRUARY));
        $this->addRecipe(new Seasonality(Month::DECEMBER, Month::JANUARY));
        $this->manager->flush();
        $this->picker = $container->get(RecipePicker::class);
    }

    private function addRecipe(Seasonality $seasonality): void
    {
        $this->manager->persist(
            new Recipe("Carbonara", new Servings(2), $seasonality, [], []),
        );
    }

    public function testReturnsAllYearRecipe(): void
    {
        $menu = new Menu(new DatePoint("2024-03-15"));

        $recipes = $this->picker->getRecipes($menu);

        self::assertCount(1, $recipes);
    }

    public function testReturnsOneMonthRecipe(): void
    {
        $menu = new Menu(new DatePoint("2024-01-15"));

        $recipes = $this->picker->getRecipes($menu);

        self::assertCount(4, $recipes);
    }

    public function testReturnsMiddleOfYearRecipe(): void
    {
        $menu = new Menu(new DatePoint("2024-02-15"));

        $recipes = $this->picker->getRecipes($menu);

        self::assertCount(2, $recipes);
    }

    public function testReturnsEndOfYearRecipe(): void
    {
        $menu = new Menu(new DatePoint("2024-12-15"));

        $recipes = $this->picker->getRecipes($menu);

        self::assertCount(2, $recipes);
    }
}
