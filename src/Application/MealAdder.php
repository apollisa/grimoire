<?php

namespace App\Application;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MealId;
use App\Domain\Menu\MenuId;
use App\Domain\Menu\MenuRepository;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;

class MealAdder
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly MenuRepository $menuRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    public function add(MenuId $id, DayOfWeek $day, RecipeId|MealId $meal): void
    {
        $this->manager->wrapInTransaction(function () use ($id, $day, $meal) {
            $menu = $this->menuRepository->ofId($id);
            if ($meal instanceof RecipeId) {
                $menu->planMeal($day, $this->recipeRepository->ofId($meal));
            } else {
                foreach ($menu->remains($day) as $remains) {
                    if ($remains->meal()->id() == $meal) {
                        $menu->planMeal($day, $remains);
                        break;
                    }
                }
            }
        });
    }
}
