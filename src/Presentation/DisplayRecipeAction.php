<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/recettes/{id}", "recipe_display", methods: Request::METHOD_GET)]
class DisplayRecipeAction extends AbstractController
{
    public function __construct(
        private readonly RecipeRepository $recipeRepository,
        private readonly FolderRepository $folderRepository,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $recipe = $this->recipeRepository->ofId(new RecipeId($id));
        return $this->render("recipes/detail.html.twig", [
            "recipe" => $recipe,
            "folder" => $this->folderRepository->ofId($recipe->folder()),
        ]);
    }
}
