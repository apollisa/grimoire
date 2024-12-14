<?php

namespace App\Domain\Recipe;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
readonly class Servings
{
    public static function zero(): self
    {
        return new self(0);
    }

    public function __construct(
        #[Column("servings", Types::SMALLINT)] private int $value,
    ) {
    }

    public function isMoreThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function minus(self $other): self
    {
        return new self($this->value - $other->value);
    }
}
