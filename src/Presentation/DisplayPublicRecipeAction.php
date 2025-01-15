<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route("/public/recettes/{slug}", "recipe_public", methods: "GET")]
class DisplayPublicRecipeAction
{
    public function __construct(
        private readonly Connection $connection,
        private readonly RecipeRepository $recipeRepository,
        private readonly FolderRepository $folderRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Template("recipes/public.html.twig")]
    public function __invoke(string $slug): array
    {
        $id = $this->connection
            ->executeQuery("SELECT id FROM recipe WHERE slug = ?", [$slug])
            ->fetchOne();
        if ($id === false) {
            throw new NotFoundHttpException("Recette inconnue");
        } else {
            $recipe = $this->recipeRepository->ofId(new RecipeId($id));
            return [
                "recipe" => $recipe,
                "folder" => $this->folderRepository->ofId($recipe->folder()),
            ];
        }
    }
}
