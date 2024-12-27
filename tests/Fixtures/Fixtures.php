<?php

namespace App\Tests\Fixtures;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Recipe\Ingredient;
use App\Domain\Recipe\Quantity;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;
use App\Domain\Recipe\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Clock\DatePoint;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $recipe = new Recipe(
            "Parmentier",
            new Servings(4),
            Seasonality::year(),
            [new Ingredient(new Quantity(1, Unit::KILOGRAMS), "patates")],
        );
        $manager->persist($recipe);
        $manager->flush();
        $menu = new Menu(new DatePoint("2024-08-26"));
        $menu->planMeal(DayOfWeek::TUESDAY, $recipe);
        $manager->persist($menu);
        $manager->flush();
    }
}
