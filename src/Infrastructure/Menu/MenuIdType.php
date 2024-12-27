<?php

namespace App\Infrastructure\Menu;

use App\Domain\Menu\MenuId;
use App\Infrastructure\Shared\IdType;

class MenuIdType extends IdType
{
    public const NAME = "menu_id";

    protected function getIdClass(): string
    {
        return MenuId::class;
    }
}
