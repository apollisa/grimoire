<?php

namespace App\Tests\Fixtures;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Recipe\Folder;
use App\Domain\Recipe\Ingredient;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;
use App\Domain\Shared\Quantity;
use App\Domain\Shared\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Clock\DatePoint;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $folder = $this->createFolder($manager);
        $recipe = $this->createRecipe($folder, $manager);
        $menu = new Menu(new DatePoint("2024-08-26"));
        $menu->planMeal(DayOfWeek::TUESDAY, $recipe);
        $manager->persist($menu);
        $manager->flush();
    }

    private function createFolder(ObjectManager $manager): Folder
    {
        $folder = new Folder("Plats");
        $manager->persist($folder);
        $manager->flush();
        return $folder;
    }

    private function createRecipe(
        Folder $folder,
        ObjectManager $manager,
    ): Recipe {
        $ingredients = [
            new Ingredient(new Quantity(1, Unit::KILOGRAMS), "patates"),
            new Ingredient(new Quantity(30, Unit::GRAMS), "beurre"),
        ];
        $recipe = new Recipe(
            $folder,
            "Parmentier",
            new Servings(4),
            Seasonality::year(),
            $ingredients,
            ["Préparer une purée"],
        );
        $manager->persist($recipe);
        $manager->flush();
        return $recipe;
    }
}
