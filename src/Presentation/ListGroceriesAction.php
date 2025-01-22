<?php

namespace App\Presentation;

use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/courses", "groceries_list", methods: Request::METHOD_GET)]
class ListGroceriesAction extends AbstractController
{
    public function __construct(private readonly MenuRepository $repository) {}

    public function __invoke(): Response
    {
        return $this->render("groceries/index.html.twig", [
            "groceries" => $this->repository->last()->groceries(),
        ]);
    }
}
