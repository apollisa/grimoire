<?php

namespace App\Domain\Shared;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Clock\DatePoint;

#[MappedSuperclass]
abstract class TimeTrackedEntity
{
    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $updatedAt;

    protected function __construct()
    {
        $this->updatedAt = new DatePoint();
    }
}
