<?php

namespace App\Presentation;

use App\Domain\Recipe\RecipeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("/recettes", "recipe_list", methods: "GET")]
class ListRecipesAction
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    #[Template("recipes/index.html.twig")]
    public function __invoke(): array
    {
        return ["recipes" => $this->repository->all()];
    }
}
