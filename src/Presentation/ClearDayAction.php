<?php

namespace App\Presentation;

use App\Application\DayClearer;
use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/menu/{id}/jour/{day}", "day_clear", methods: Request::METHOD_POST)]
class ClearDayAction extends AbstractController
{
    public function __construct(private readonly DayClearer $clearer)
    {
    }

    public function __invoke(int $id, DayOfWeek $day): Response
    {
        $this->clearer->clear(new MenuId($id), $day);
        return $this->redirectToRoute(
            "menu_display",
            status: Response::HTTP_SEE_OTHER,
        );
    }
}
