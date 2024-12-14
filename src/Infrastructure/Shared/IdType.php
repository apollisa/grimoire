<?php

namespace App\Infrastructure\Shared;

use App\Domain\Shared\Id;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class IdType extends Type
{
    final public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    final public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?Id {
        return $value === null ? null : new ($this->getIdClass())($value);
    }

    /**
     * @return class-string<Id>
     */
    abstract protected function getIdClass(): string;

    final public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?int {
        return $value?->value();
    }

    final public function getBindingType(): ParameterType
    {
        return ParameterType::INTEGER;
    }
}
