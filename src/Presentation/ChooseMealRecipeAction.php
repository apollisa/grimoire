<?php

namespace App\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use App\Domain\Menu\MenuRepository;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("{id}/recettes", "menu_recipes", methods: "GET")]
class ChooseMealRecipeAction
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly RecipePicker $picker,
    ) {
    }

    #[Template("menus/_new-meal.html.twig")]
    public function __invoke(
        int $id,
        #[MapQueryParameter("jour")] DayOfWeek $day,
    ): array {
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
        return [
            "menu" => $menu,
            "day" => $day->value,
            "remains" => $remains,
            "recipes" => $recipes,
            "rooster" => $rooster,
        ];
    }
}
