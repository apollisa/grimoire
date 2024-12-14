<?php

namespace App\Presentation;

use App\Domain\Menu\MenuRepository;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route(methods: "GET")]
class DisplayMenuAction
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    #[Template("base.html.twig")]
    public function __invoke(): array
    {
        $menus = $this->menuRepository->upcoming();
        $recipes = [];
        foreach ($menus as $menu) {
            foreach ($menu->days() as $day) {
                foreach ($day->meals() as $meal) {
                    $id = $meal->recipe();
                    $recipes[$id->value()] = $this->recipeRepository->ofId($id);
                }
            }
        }
        return ["menus" => $menus, "recipes" => $recipes];
    }
}
