<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\RecipeId;
use App\Infrastructure\Shared\IdType;

class RecipeIdType extends IdType
{
    public const NAME = "recipe_id";

    protected function getIdClass(): string
    {
        return RecipeId::class;
    }
}
