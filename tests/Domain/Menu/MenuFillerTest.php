<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Menu\MenuFiller;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Servings;
use App\Tests\Fixtures\TestRecipe;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

class MenuFillerTest extends TestCase
{
    private Recipe $recipe;
    private MenuFiller $filler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $picker = self::createStub(RecipePicker::class);
        $this->recipe = new TestRecipe("Parmentier", new Servings(4));
        $picker->method("getRecipes")->willReturn([$this->recipe]);
        $this->filler = new MenuFiller($picker);
    }

    public function testFillsUsingRemains(): void
    {
        $previous = new Menu(new DatePoint());
        $previous->planMeal(DayOfWeek::SUNDAY, $this->recipe);
        $menu = new Menu(new DatePoint(), $previous->remains());

        $this->filler->fill($menu);

        self::assertTrue($menu->days()->first()->meals()[0]->isRemains());
    }
}
