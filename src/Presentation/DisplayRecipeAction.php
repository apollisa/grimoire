<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("/recettes/{id}", "recipe_display", methods: "GET")]
class DisplayRecipeAction
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
        private readonly FolderRepository $folderRepository,
    ) {
    }

    #[Template("recipes/detail.html.twig")]
    public function __invoke(int $id): array
    {
        $recipe = $this->recipeRepository->ofId(new RecipeId($id));
        return [
            "recipe" => $recipe,
            "folder" => $this->folderRepository->ofId($recipe->folder()),
        ];
    }
}
