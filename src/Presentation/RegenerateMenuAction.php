<?php

namespace App\Presentation;

use App\Application\MenuRegenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/menu", "menu_regenerate", methods: Request::METHOD_POST)]
class RegenerateMenuAction extends AbstractController
{
    public function __construct(private readonly MenuRegenerator $regenerator)
    {
    }

    public function __invoke(): Response
    {
        $this->regenerator->regenerate();
        return $this->redirectToRoute(
            "menu_display",
            status: Response::HTTP_SEE_OTHER,
        );
    }
}
