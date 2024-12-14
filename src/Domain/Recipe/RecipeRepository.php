<?php

namespace App\Domain\Recipe;

interface RecipeRepository
{
    public function ofId(RecipeId $id): Recipe;
}
