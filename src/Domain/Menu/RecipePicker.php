<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Month;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;

class RecipePicker
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    /**
     * @return Recipe[]
     */
    public function getRecipes(Menu $menu): array
    {
        return $this->repository->ofMonth(Month::of($menu->monday()));
    }
}
