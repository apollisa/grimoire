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
     * @return Recipe[]
     */
    public function ofMonth(Month $month): array;

    /**
     * @return iterable<Recipe>
     */
    public function all(): iterable;
}
