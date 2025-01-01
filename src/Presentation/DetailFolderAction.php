<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("/dossiers/{id}", "folder_detail", methods: "GET")]
class DetailFolderAction
{
    public function __construct(
        private readonly FolderRepository $folderRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    #[Template("folders/detail.html.twig")]
    public function __invoke(int $id): array
    {
        $folder = $this->folderRepository->ofId(new FolderId($id));
        return [
            "folder" => $folder,
            "recipes" => $this->recipeRepository->ofFolder($folder),
        ];
    }
}
