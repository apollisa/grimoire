<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use Random\Randomizer;

class MenuFiller
{
    private readonly Randomizer $randomizer;

    public function __construct(private readonly RecipePicker $picker)
    {
        $this->randomizer = new Randomizer();
    }

    public function fill(Menu $menu): void
    {
        $rooster = $this->picker->getRecipes($menu);
        $recipes = $this->shuffle($rooster);
        foreach (DayOfWeek::cases() as $day) {
            $menu->clear($day);
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
