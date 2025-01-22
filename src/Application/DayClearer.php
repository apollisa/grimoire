<?php

namespace App\Application;

use App\Domain\Menu\DayOfWeek;
use App\Domain\Menu\MenuId;
use App\Domain\Menu\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class DayClearer
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly MenuRepository $repository,
    ) {}

    public function clear(MenuId $id, DayOfWeek $day): void
    {
        $this->manager->wrapInTransaction(
            fn() => $this->repository->ofId($id)?->clear($day),
        );
    }
}
