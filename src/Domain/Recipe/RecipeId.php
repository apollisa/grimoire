<?php

namespace App\Domain\Recipe;

use Stringable;

readonly class RecipeId implements Stringable
{
    public function __construct(private int $value)
    {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
