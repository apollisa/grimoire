<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\Ingredient;
use App\Domain\Shared\Quantity;
use App\Domain\Shared\Unit;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class IngredientsType extends Type
{
    public const NAME = "ingredients";

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): string {
        return json_encode(
            array_map(function (Ingredient $ingredient): array {
                $quantity = $ingredient->quantity();
                $name = $ingredient->name();
                return $quantity === null
                    ? ["name" => $name]
                    : [
                        "quantity" => [
                            "value" => $quantity->value(),
                            "unit" => $quantity->unit()->value,
                        ],
                        "name" => $name,
                    ];
            }, $value),
        );
    }

    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): array {
        return array_map(function (array $ingredient): Ingredient {
            $name = $ingredient["name"];
            if (array_key_exists("quantity", $ingredient)) {
                $quantity = $ingredient["quantity"];
                return new Ingredient(
                    new Quantity(
                        $quantity["value"],
                        Unit::from($quantity["unit"]),
                    ),
                    $name,
                );
            } else {
                return new Ingredient(null, $name);
            }
        }, json_decode($value, true));
    }

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return $platform->getJsonTypeDeclarationSQL($column);
    }
}
