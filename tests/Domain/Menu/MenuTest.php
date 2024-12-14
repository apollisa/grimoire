<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Servings;
use App\Tests\Fixtures\TestRecipe;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

class MenuTest extends TestCase
{
    private Recipe $recipe;

    protected function setUp(): void
    {
        $this->recipe = new TestRecipe("Parmentier", new Servings(4));
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
        $menu->planMeal(
            DayOfWeek::TUESDAY,
            new TestRecipe("Parmentier", new Servings(4)),
        );

        self::assertNull($menu->takeRemains(DayOfWeek::MONDAY));
    }
}
