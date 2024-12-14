<?php

namespace App\Domain\Menu;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Day
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ManyToOne(inversedBy: "days")]
    private Menu $menu;

    #[Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $date;

    public function __construct(Menu $menu, DateTimeInterface $date)
    {
        $this->menu = $menu;
        $this->date = $date;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
