<?php

namespace App\Tests\Fixtures;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;

class TestRecipe extends Recipe
{
    public function id(): RecipeId
    {
        return new RecipeId(1);
    }
}
