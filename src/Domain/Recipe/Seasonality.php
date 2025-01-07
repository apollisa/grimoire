<?php

namespace App\Domain\Recipe;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
readonly class Seasonality
{
    public static function year(): self
    {
        return new self(Month::JANUARY, Month::DECEMBER);
    }

    public function __construct(
        #[Column] private Month $starts,
        #[Column] private Month $ends,
    ) {
    }

    public function isYearRound(): bool
    {
        return $this->starts() === Month::JANUARY &&
            $this->ends() === Month::DECEMBER;
    }

    public function starts(): Month
    {
        return $this->starts;
    }

    public function ends(): Month
    {
        return $this->ends;
    }
}
