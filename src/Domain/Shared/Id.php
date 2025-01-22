<?php

namespace App\Domain\Shared;

use Stringable;

abstract readonly class Id implements Stringable
{
    final public function __construct(private int $value) {}

    final public function value(): int
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return $this->value();
    }
}
