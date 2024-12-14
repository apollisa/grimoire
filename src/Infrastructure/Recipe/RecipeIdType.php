<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\RecipeId;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class RecipeIdType extends Type
{
    public const NAME = "recipe_id";

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?RecipeId {
        return $value === null ? null : new RecipeId($value);
    }

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?int {
        return $value?->value();
    }

    public function getBindingType(): ParameterType
    {
        return ParameterType::INTEGER;
    }
}
