<?php

namespace App\Presentation;

use App\Domain\Recipe\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/recettes", "recipe_list", methods: Request::METHOD_GET)]
class ListRecipesAction extends AbstractController
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    public function __invoke(): Response
    {
        return $this->render("recipes/index.html.twig", [
            "recipes" => $this->repository->all(),
        ]);
    }
}
