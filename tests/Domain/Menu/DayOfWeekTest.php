<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\DayOfWeek;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

class DayOfWeekTest extends TestCase
{
    public function testDoesNotAdjustIfAlreadyAtDay(): void
    {
        $day = new DatePoint("2024-12-16");

        self::assertEquals($day, DayOfWeek::MONDAY->adjustInto($day));
    }

    public function testDoesNotMoveToNextWeekIfDayPassed(): void
    {
        $day = new DatePoint("2024-12-17");

        $monday = new DatePoint("2024-12-16");
        self::assertEquals($monday, DayOfWeek::MONDAY->adjustInto($day));
    }
}
