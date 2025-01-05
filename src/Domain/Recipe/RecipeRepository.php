<?php

namespace App\Domain\Recipe;

interface RecipeRepository
{
    public function ofId(RecipeId $id): Recipe;

    /**
     * @return iterable<Recipe>
     */
    public function ofFolder(Folder $folder): iterable;

    /**
     * @param Folder[] $folders
     * @return Recipe[]
     */
    public function inFolderAndMonth(array $folders, Month $month): array;

    /**
     * @return iterable<Recipe>
     */
    public function all(): iterable;

    public function add(Recipe $recipe): Recipe;
}
