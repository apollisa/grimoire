<?php

namespace App\Presentation;

use App\Domain\Menu\MenuRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route(methods: "GET")]
class DisplayMenuAction
{
    public function __construct(private readonly MenuRepository $repository)
    {
    }

    #[Template("base.html.twig")]
    public function __invoke(): array
    {
        return ["menus" => $this->repository->upcoming()];
    }
}
