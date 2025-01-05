<?php

namespace App\Tests\Application;

use App\Application\IngredientTransformer;
use App\Domain\Recipe\Ingredient;
use App\Domain\Shared\Quantity;
use App\Domain\Shared\Unit;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngredientTransformerTest extends KernelTestCase
{
    private IngredientTransformer $transformer;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->transformer = $container->get(IngredientTransformer::class);
    }

    public function testTransformIngredientWithNoQuantity(): void
    {
        self::assertEquals(
            [new Ingredient(null, "huile de colza")],
            $this->transformer->transform(["huile de colza"]),
        );
    }

    public function testTransformIngredientWithNoUnit(): void
    {
        self::assertEquals(
            [new Ingredient(new Quantity(1), "pâte brisée")],
            $this->transformer->transform(["1 pâte brisée"]),
        );
    }

    public function testTransformWithUnit(): void
    {
        self::assertEquals(
            [new Ingredient(new Quantity(1, Unit::KILOGRAMS), "patates")],
            $this->transformer->transform(["1 kg patates"]),
        );
    }

    #[DataProvider("teaspoonProvider")]
    public function testTransformWithComplicatedUnit(string $unit): void
    {
        self::assertEquals(
            [new Ingredient(new Quantity(1, Unit::TSP), "patates")],
            $this->transformer->transform(["1 $unit patates"]),
        );
    }

    public static function teaspoonProvider(): iterable
    {
        return [["tsp."], ["tsp"], ["càc"], ["c.-à-c."], ["c.-a-c."], ["CAC"]];
    }
}
