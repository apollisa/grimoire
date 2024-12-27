<?php

namespace App\Domain\Recipe;

readonly class Ingredient
{
    public function __construct(
        private ?Quantity $quantity,
        private string $name,
    ) {
    }

    public function quantity(): ?Quantity
    {
        return $this->quantity;
    }

    public function name(): string
    {
        return $this->name;
    }
}
