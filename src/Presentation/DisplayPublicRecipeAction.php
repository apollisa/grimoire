<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[
    Route(
        "/public/recettes/{slug}",
        "recipe_public",
        methods: Request::METHOD_GET,
    ),
]
class DisplayPublicRecipeAction extends AbstractController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly RecipeRepository $recipeRepository,
        private readonly FolderRepository $folderRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(string $slug, Request $request): Response
    {
        $id = $this->getRecipeId($slug);
        $recipe = $this->recipeRepository->ofId($id);
        $response = new Response();
        $response->setLastModified($recipe->updatedAt());
        if (!$response->isNotModified($request)) {
            $folder = $this->folderRepository->ofId($recipe->folder());
            $this->render(
                "recipes/public.html.twig",
                ["recipe" => $recipe, "folder" => $folder],
                $response,
            );
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
            throw $this->createNotFoundException("Recette inconnue");
        }
        return new RecipeId($id);
    }
}
