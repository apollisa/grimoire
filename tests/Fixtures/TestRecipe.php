<?php

namespace App\Tests\Fixtures;

use App\Domain\Recipe\Ingredient;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;

class TestRecipe extends Recipe
{
    /**
     * @param iterable<Ingredient> $ingredients
     */
    public function __construct(
        string $name,
        Servings $servings,
        array $ingredients,
    ) {
        parent::__construct(
            $name,
            $servings,
            Seasonality::year(),
            $ingredients,
            [],
        );
    }

    public function id(): RecipeId
    {
        return new RecipeId(1);
    }
}
