<?php

namespace App\Tests\Fixtures;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;

class TestRecipe extends Recipe
{
    public function __construct(string $name, Servings $servings)
    {
        parent::__construct($name, $servings, Seasonality::year(), []);
    }

    public function id(): RecipeId
    {
        return new RecipeId(1);
    }
}
