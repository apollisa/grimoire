<?php

namespace App\Application;

use App\Domain\Menu\MenuFiller;
use App\Domain\Menu\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class MenuRegenerator
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly MenuRepository $repository,
        private readonly MenuFiller $filler,
    ) {}

    public function regenerate(): void
    {
        $this->manager->wrapInTransaction(function (): void {
            $menu = $this->repository->last();
            if ($menu) {
                $this->filler->fill($menu);
            }
        });
    }
}
