<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use Psr\Clock\ClockInterface;
use Random\Randomizer;

class MenuFactory
{
    private readonly Randomizer $randomizer;

    public function __construct(
        private readonly ClockInterface $clock,
        private readonly MenuRepository $repository,
        private readonly RecipePicker $picker,
    ) {
        $this->randomizer = new Randomizer();
    }

    public function create(): Menu
    {
        $monday = $this->clock->now()->modify("monday next week");
        $menu = new Menu($monday, $this->getPreviousRemains());
        $rooster = $this->picker->getRecipes($menu);
        $recipes = $this->shuffle($rooster);
        foreach (DayOfWeek::cases() as $day) {
            if ($meal = $menu->takeRemains($day)) {
                $menu->planMeal($day, $meal);
                continue;
            }
            if (empty($recipes)) {
                $recipes = $this->shuffle($rooster);
            }
            $recipe = array_pop($recipes);
            $menu->planMeal($day, $recipe);
        }
        return $menu;
    }

    /**
     * @return Remains[]
     */
    private function getPreviousRemains(): array
    {
        return array_map(
            fn(Remains $remains): Remains => new MenuRemains($remains),
            $this->repository->last()?->remains() ?? [],
        );
    }

    /**
     * @param Recipe[] $recipes
     * @return Recipe[]
     */
    private function shuffle(array $recipes): array
    {
        return $this->randomizer->shuffleArray($recipes);
    }
}
