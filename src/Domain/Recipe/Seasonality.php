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
        #[Column(enumType: Month::class)] private Month $starts,
        #[Column(enumType: Month::class)] private Month $ends,
    ) {
    }
}
