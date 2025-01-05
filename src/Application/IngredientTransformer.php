<?php

namespace App\Application;

use App\Domain\Recipe\Ingredient;
use App\Domain\Shared\Quantity;
use App\Domain\Shared\Unit;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Transliterator;

class IngredientTransformer
{
    private const PATTERN = "/(?:(?<quantity>\d+(?:,\d+)?)\s+)?(?:(?<unit>\S+)\s+)?(?<ingredient>.*)/i";

    private readonly Transliterator $transliterator;
    private readonly array $units;

    public function __construct(
        #[Autowire(param: "app.units")] array $translations,
    ) {
        $this->transliterator = Transliterator::createFromRules(
            ":: NFD;" .
                ":: [[:Nonspacing Mark:] [.-]] Remove;" .
                ":: Lower();" .
                ":: NFC;",
        );
        $units = [];
        foreach (Unit::cases() as $unit) {
            $value = $unit->value;
            $units[$this->simplify($value)] = $unit;
            if (array_key_exists($value, $translations)) {
                $translation = $translations[$value];
                $units[$this->simplify($translation)] = $unit;
            }
        }
        $this->units = $units;
    }

    private function simplify(string $unit): string
    {
        return $this->transliterator->transliterate($unit);
    }

    /**
     * @param string[] $values
     * @return Ingredient[]
     */
    public function transform(array $values): array
    {
        $ingredients = [];
        foreach ($values as $ingredient) {
            $matches = $this->getParts($ingredient);
            $quantity = $this->getQuantity(
                $matches["quantity"],
                $matches["unit"],
            );
            $name =
                $quantity === null || $quantity->unit() === Unit::UNITS
                    ? "{$matches["unit"]} {$matches["ingredient"]}"
                    : $matches["ingredient"];
            $ingredients[] = new Ingredient($quantity, trim($name));
        }
        return $ingredients;
    }

    private function getParts(string $ingredient): array
    {
        $matches = [];
        preg_match(self::PATTERN, $ingredient, $matches);
        return $matches;
    }

    private function getQuantity(?string $quantity, ?string $unit): ?Quantity
    {
        $value = floatval(str_replace(",", ".", $quantity));
        if ($value === 0.) {
            return null;
        } else {
            return new Quantity(
                $value,
                $this->units[$this->simplify($unit)] ?? Unit::UNITS,
            );
        }
    }
}
