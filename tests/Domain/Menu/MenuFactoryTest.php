<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\Menu;
use App\Domain\Menu\MenuFactory;
use App\Domain\Menu\MenuRepository;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Servings;
use App\Tests\Fixtures\TestRecipe;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Clock\MockClock;

class MenuFactoryTest extends TestCase
{
    private MenuRepository $repository;
    private Recipe $recipe;
    private MenuFactory $factory;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $clock = new MockClock("2024-12-16");
        $this->repository = self::createStub(MenuRepository::class);
        $picker = self::createStub(RecipePicker::class);
        $this->recipe = new TestRecipe("Parmentier", new Servings(4));
        $picker->method("getRecipes")->willReturn([$this->recipe]);
        $this->factory = new MenuFactory($clock, $this->repository, $picker);
    }

    public function testCreatesUsingRemains(): void
    {
        $previous = new Menu(new DatePoint());
        $previous->planMeal(DayOfWeek::SUNDAY, $this->recipe);
        $this->repository->method("last")->willReturn($previous);

        $menu = $this->factory->create();

        self::assertTrue($menu->days()->first()->meals()[0]->isRemains());
    }

    public function testCreatesNextMonday(): void
    {
        $menu = $this->factory->create();

        self::assertEquals(new DatePoint("2024-12-23"), $menu->monday());
    }
}
