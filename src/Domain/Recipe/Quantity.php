<?php

namespace App\Domain\Recipe;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
readonly class Quantity
{
    public function __construct(
        #[Column(type: Types::SMALLFLOAT)] private float $value,
        #[Column] private Unit $unit = Unit::UNITS,
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function unit(): Unit
    {
        return $this->unit;
    }
}
