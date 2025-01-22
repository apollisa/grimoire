<?php

namespace App\Presentation;

use App\Application\MealAdder;
use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("{id}/jours/{day}/repas", "menu_plan", methods: Request::METHOD_POST)]
class PlanMealAction extends AbstractController
{
    public function __construct(private readonly MealAdder $adder) {}

    public function __invoke(
        int $id,
        DayOfWeek $day,
        Request $request,
    ): Response {
        $meal = $request->request->get("meal");
        $this->adder->add(new MenuId($id), $day, MealIdConverter::toId($meal));
        return $this->redirectToRoute(
            "menu_display",
            status: Response::HTTP_SEE_OTHER,
        );
    }
}
