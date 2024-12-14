<?php

namespace App\Infrastructure\Menu;

use App\Domain\Menu\MealId;
use App\Infrastructure\Shared\IdType;

class MealIdType extends IdType
{
    public const NAME = "meal_id";

    protected function getIdClass(): string
    {
        return MealId::class;
    }
}
