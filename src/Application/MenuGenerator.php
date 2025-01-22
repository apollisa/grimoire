<?php

namespace App\Application;

use App\Domain\Menu\MenuFactory;
use App\Domain\Menu\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class MenuGenerator
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly MenuFactory $factory,
        private readonly MenuRepository $repository,
    ) {}

    public function generate(): void
    {
        $this->manager->wrapInTransaction(function (): void {
            $menu = $this->factory->create();
            $this->repository->add($menu);
        });
    }
}
