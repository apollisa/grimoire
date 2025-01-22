<?php

namespace App\Presentation;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use App\Domain\Menu\MenuRepository;
use App\Domain\Menu\RecipePicker;
use App\Domain\Recipe\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route("{id}/recettes", "menu_recipes", methods: Request::METHOD_GET)]
class ChooseMealRecipeAction extends AbstractController
{
    use StimulusRequestTrait;

    private const TEMPLATE = "menus/new-meal.html.twig";

    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly RecipePicker $picker,
    ) {}

    public function __invoke(
        int $id,
        #[MapQueryParameter("jour")] DayOfWeek $day,
        Request $request,
    ): Response {
        $isStimulusRequest = $this->isStimulusRequest($request);
        $menu = $this->menuRepository->ofId(new MenuId($id));
        $remains = $recipes = [];
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
            "fragment" => $isStimulusRequest,
            "menu" => $menu,
            "day" => $day->value,
            "remains" => $remains,
            "recipes" => $recipes,
            "rooster" => $rooster,
        ];
        return $isStimulusRequest
            ? $this->renderBlock(self::TEMPLATE, "content", $parameters)
            : $this->render(self::TEMPLATE, $parameters);
    }
}
