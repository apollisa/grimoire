<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Recipe\Ingredient;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Servings;
use App\Domain\Shared\Quantity;
use App\Domain\Shared\Unit;
use App\Tests\Fixtures\TestRecipe;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

class MenuTest extends TestCase
{
    private Recipe $recipe;

    protected function setUp(): void
    {
        $this->recipe = new TestRecipe("Parmentier", new Servings(4), [
            new Ingredient(new Quantity(1, Unit::KILOGRAMS), "patates"),
        ]);
    }

    public function testReturnsNullIfNoRemains(): void
    {
        $menu = new Menu(new DatePoint());

        self::assertNull($menu->takeRemains(DayOfWeek::MONDAY));
    }

    public function testReturnsPreviousRemainsIfAny(): void
    {
        $previous = new Menu(new DatePoint());
        $previous->planMeal(DayOfWeek::MONDAY, $this->recipe);
        $menu = new Menu(new DatePoint(), $previous->remains());

        self::assertNotNull($menu->takeRemains(DayOfWeek::MONDAY));
    }

    public function testReturnsOwnRemainsIfPresent(): void
    {
        $menu = new Menu(new DatePoint());
        $menu->planMeal(DayOfWeek::TUESDAY, $this->recipe);

        self::assertNotNull($menu->takeRemains(DayOfWeek::WEDNESDAY));
    }

    public function testDoesNotReturnFutureRemains(): void
    {
        $menu = new Menu(new DatePoint());
        $menu->planMeal(DayOfWeek::TUESDAY, $this->recipe);

        self::assertNull($menu->takeRemains(DayOfWeek::MONDAY));
    }

    public function testPlanningAddsGroceries(): void
    {
        $menu = new Menu(new DatePoint());

        $recipe = $this->recipe;
        $menu->planMeal(DayOfWeek::MONDAY, $recipe);

        $ingredient = $recipe->ingredients()[0];
        $grocery = $menu->groceries()[0];
        self::assertEquals($ingredient->quantity(), $grocery->quantity());
        self::assertEquals($ingredient->name(), $grocery->name());
    }

    public function testClearingRemovesGroceries(): void
    {
        $menu = new Menu(new DatePoint());
        $day = DayOfWeek::MONDAY;
        $menu->planMeal($day, $this->recipe);

        $menu->clear($day);

        self::assertEmpty($menu->groceries());
    }
}
