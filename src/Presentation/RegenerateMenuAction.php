<?php

namespace App\Presentation;

use App\Application\MenuRegenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsController, Route("/menu", "menu_regenerate", methods: "POST")]
class RegenerateMenuAction
{
    public function __construct(
        private readonly MenuRegenerator $regenerator,
        private readonly UrlGeneratorInterface $generator,
    ) {
    }

    public function __invoke(): Response
    {
        $this->regenerator->regenerate();
        return new RedirectResponse(
            $this->generator->generate("menu_display"),
            Response::HTTP_SEE_OTHER,
        );
    }
}
