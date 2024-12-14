<?php

namespace App\Domain\Menu;

interface MenuRepository
{
    /**
     * @return iterable<Menu>
     */
    public function upcoming(): iterable;
}
