<?php

namespace App\Domain\Menu;

interface MenuRepository
{
    public function last(): ?Menu;

    /**
     * @return iterable<Menu>
     */
    public function upcoming(): iterable;

    public function add(Menu $menu);
}
