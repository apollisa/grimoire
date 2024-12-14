<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\RecipeId;

interface Remains
{
    public function recipe(): RecipeId;
    public function meal(): Meal;
    public function toMeal(Day $day): Meal;
}
