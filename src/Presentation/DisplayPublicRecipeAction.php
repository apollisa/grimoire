<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
#[Route("/public/recettes/{slug}", "recipe_public", methods: "GET")]
class DisplayPublicRecipeAction
{
    public function __construct(
        private readonly Connection $connection,
        private readonly RecipeRepository $recipeRepository,
        private readonly FolderRepository $folderRepository,
        private readonly Environment $twig,
    ) {
    }

    /**
     * @throws Exception
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function __invoke(string $slug, Request $request): Response
    {
        $id = $this->getRecipeId($slug);
        $recipe = $this->recipeRepository->ofId($id);
        $response = new Response();
        $response->setLastModified($recipe->updatedAt());
        if (!$response->isNotModified($request)) {
            $folder = $this->folderRepository->ofId($recipe->folder());
            $content = $this->twig->render("recipes/public.html.twig", [
                "recipe" => $recipe,
                "folder" => $folder,
            ]);
            $response->setContent($content);
        }
        return $response;
    }

    /**
     * @throws Exception
     */
    private function getRecipeId(string $slug): RecipeId
    {
        $id = $this->connection
            ->executeQuery("SELECT id FROM recipe WHERE slug = ?", [$slug])
            ->fetchOne();
        if ($id === false) {
            throw new NotFoundHttpException("Recette inconnue");
        }
        return new RecipeId($id);
    }
}
