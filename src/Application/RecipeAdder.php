<?php

namespace App\Application;

use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\Month;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\Recipe\Seasonality;
use App\Domain\Recipe\Servings;
use Doctrine\ORM\EntityManagerInterface;

class RecipeAdder
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly FolderRepository $folderRepository,
        private readonly IngredientTransformer $transformer,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    public function add(AddRecipeCommand $command): Recipe
    {
        return $this->manager->wrapInTransaction(function () use ($command) {
            $recipe = new Recipe(
                $this->folderRepository->ofId(new FolderId($command->folder)),
                $command->name,
                new Servings($command->servings),
                new Seasonality(
                    Month::from($command->starts),
                    Month::from($command->ends),
                ),
                $this->transformer->transform($command->ingredients),
                $command->instructions,
            );
            return $this->recipeRepository->add($recipe);
        });
    }
}
