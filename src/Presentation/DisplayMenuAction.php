<?php

namespace App\Presentation;

use App\Domain\Menu\MenuRepository;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: "menu_display", methods: Request::METHOD_GET)]
class DisplayMenuAction extends AbstractController
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    public function __invoke(): Response
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
        return $this->render("menus/index.html.twig", [
            "menus" => $menus,
            "recipes" => $recipes,
        ]);
    }
}
