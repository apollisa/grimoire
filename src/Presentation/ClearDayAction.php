<?php

namespace App\Presentation;

use App\Application\DayClearer;
use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsController, Route("/menu/{id}/jour/{day}", "day_clear", methods: "POST")]
class ClearDayAction
{
    public function __construct(
        private readonly DayClearer $clearer,
        private readonly UrlGeneratorInterface $generator,
    ) {
    }

    public function __invoke(int $id, DayOfWeek $day): Response
    {
        $this->clearer->clear(new MenuId($id), $day);
        return new RedirectResponse(
            $this->generator->generate("menu_display"),
            Response::HTTP_SEE_OTHER,
        );
    }
}
