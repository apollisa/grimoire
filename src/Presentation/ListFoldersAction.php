<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("/dossiers", "folder_list", methods: "GET")]
class ListFoldersAction
{
    public function __construct(private readonly FolderRepository $repository)
    {
    }

    #[Template("folders/index.html.twig")]
    public function __invoke(): array
    {
        return ["folders" => $this->repository->all()];
    }
}
