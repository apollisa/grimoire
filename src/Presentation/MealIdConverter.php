<?php

namespace App\Presentation;

use App\Domain\Menu\MealId;
use App\Domain\Recipe\RecipeId;

class MealIdConverter
{
    private const RECIPE_PREFIX = "REC";

    public static function toId(string $id): RecipeId|MealId
    {
        [$prefix, $value] = explode("-", $id);
        return $prefix === self::RECIPE_PREFIX
            ? new RecipeId($value)
            : new MealId($value);
    }

    public static function toString(RecipeId|MealId $id): string
    {
        $prefix = $id instanceof RecipeId ? self::RECIPE_PREFIX : "REM";
        return "$prefix-$id";
    }
}
