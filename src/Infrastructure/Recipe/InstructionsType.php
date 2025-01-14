<?php

namespace App\Infrastructure\Recipe;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class InstructionsType extends JsonType
{
    public const NAME = "instructions";

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): string {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
