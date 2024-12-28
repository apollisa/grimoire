<?php

namespace App\Presentation;

use App\Domain\Menu\MenuRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController, Route("/courses", "groceries_list", methods: "GET")]
class ListGroceriesAction
{
    public function __construct(private readonly MenuRepository $repository)
    {
    }

    #[Template("groceries/index.html.twig")]
    public function __invoke(): array
    {
        return ["groceries" => $this->repository->last()->groceries()];
    }
}
