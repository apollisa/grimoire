<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\RecipeId;

readonly class MenuRemains implements Remains
{
    private RecipeId $recipe;
    private Meal $meal;

    public function __construct(Remains $remains)
    {
        $this->recipe = $remains->recipe();
        $this->meal = $remains->meal();
    }

    public function recipe(): RecipeId
    {
        return $this->recipe;
    }

    public function meal(): Meal
    {
        return $this->meal;
    }

    public function toMeal(Day $day): Meal
    {
        return Meal::fromRemains($this, $day);
    }
}
