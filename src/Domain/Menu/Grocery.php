<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Ingredient;
use App\Domain\Shared\Quantity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Grocery
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private int $id;

    #[ManyToOne(inversedBy: "groceries")]
    private Day $day;

    #[Embedded]
    private ?Quantity $quantity;

    #[Column]
    private string $name;

    public function __construct(Day $day, Ingredient $ingredient)
    {
        $this->day = $day;
        $this->quantity = $ingredient->quantity();
        $this->name = $ingredient->name();
    }

    public function quantity(): ?Quantity
    {
        return $this->quantity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
