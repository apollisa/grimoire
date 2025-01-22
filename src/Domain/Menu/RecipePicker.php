<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\Month;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;

class RecipePicker
{
    public function __construct(
        private readonly FolderRepository $folderRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {}

    /**
     * @return Recipe[]
     */
    public function getRecipes(Menu $menu): array
    {
        $folders = $this->folderRepository->includedInMenus();
        return $this->recipeRepository->inFolderAndMonth(
            $folders,
            Month::of($menu->monday()),
        );
    }
}
