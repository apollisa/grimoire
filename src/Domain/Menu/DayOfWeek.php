<?php

namespace App\Domain\Menu;

use DateTimeInterface;

enum DayOfWeek: int
{
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;
    case SUNDAY = 7;

    public static function of(DateTimeInterface $date): self
    {
        return self::from($date->format("N"));
    }

    public function adjustInto(DateTimeInterface $date): DateTimeInterface
    {
        return $date->modify("$this->name this week");
    }
}
