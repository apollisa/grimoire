<?php

namespace App\Domain\Menu;

use Psr\Clock\ClockInterface;

class MenuFactory
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly MenuRepository $repository,
        private readonly MenuFiller $filler,
    ) {
    }

    public function create(): Menu
    {
        $monday = $this->clock->now()->modify("monday next week");
        $menu = new Menu($monday, $this->getPreviousRemains());
        $this->filler->fill($menu);
        return $menu;
    }

    /**
     * @return Remains[]
     */
    private function getPreviousRemains(): array
    {
        return array_map(
            fn(Remains $remains): Remains => new MenuRemains($remains),
            $this->repository->last()?->remains() ?? [],
        );
    }
}
