<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/dossiers/{id}", "folder_detail", methods: Request::METHOD_GET)]
class DetailFolderAction extends AbstractController
{
    public function __construct(
        private readonly FolderRepository $folderRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $folder = $this->folderRepository->ofId(new FolderId($id));
        return $this->render("folders/detail.html.twig", [
            "folder" => $folder,
            "recipes" => $this->recipeRepository->ofFolder($folder),
        ]);
    }
}
