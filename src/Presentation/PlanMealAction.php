<?php

namespace App\Presentation;

use App\Application\MealAdder;
use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsController, Route("{id}/jours/{day}/repas", "menu_plan", methods: "POST")]
class PlanMealAction
{
    public function __construct(
        private readonly MealAdder $adder,
        private readonly UrlGeneratorInterface $generator,
    ) {
    }

    public function __invoke(
        int $id,
        DayOfWeek $day,
        Request $request,
    ): Response {
        $meal = $request->request->get("meal");
        $this->adder->add(new MenuId($id), $day, MealIdConverter::toId($meal));
        return new RedirectResponse(
            $this->generator->generate("menu_display"),
            Response::HTTP_SEE_OTHER,
        );
    }
}
