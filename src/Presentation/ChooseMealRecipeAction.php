<?php

namespace App\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use App\Domain\Menu\MenuRepository;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController, Route("{id}/recettes", "menu_recipes", methods: "GET")]
class ChooseMealRecipeAction extends FragmentAction
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly RecipePicker $picker,
        RequestStack $stack,
        Environment $twig,
    ) {
        parent::__construct($stack, $twig);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(
        int $id,
        #[MapQueryParameter("jour")] DayOfWeek $day,
    ): Response {
        $menu = $this->menuRepository->ofId(new MenuId($id));
        $remains = [];
        $recipes = [];
        foreach ($menu->remains($day) as $meal) {
            $remains[MealIdConverter::toString($meal->meal()->id())] = $meal;
            $recipe = $meal->recipe();
            $recipes[$recipe->value()] = $this->recipeRepository->ofId($recipe);
        }
        $rooster = [];
        foreach ($this->picker->getRecipes($menu) as $recipe) {
            $rooster[MealIdConverter::toString($recipe->id())] = $recipe;
        }
        $parameters = [
            "menu" => $menu,
            "day" => $day->value,
            "remains" => $remains,
            "recipes" => $recipes,
            "rooster" => $rooster,
        ];
        return $this->render("menus/new-meal.html.twig", $parameters);
    }
}
