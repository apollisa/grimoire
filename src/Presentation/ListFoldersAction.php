<?php

namespace App\Presentation;

use App\Domain\Recipe\FolderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/dossiers", "folder_list", methods: Request::METHOD_GET)]
class ListFoldersAction extends AbstractController
{
    public function __construct(private readonly FolderRepository $repository)
    {
    }

    public function __invoke(): Response
    {
        return $this->render("folders/index.html.twig", [
            "folders" => $this->repository->all(),
        ]);
    }
}
