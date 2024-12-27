<?php

namespace App\Domain\Recipe;

use App\Infrastructure\Recipe\IngredientsType;
use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Recipe
{
    #[Id, GeneratedValue, Column(type: RecipeIdType::NAME)]
    private ?RecipeId $id = null;

    #[Column]
    private string $name;

    #[Embedded(columnPrefix: false)]
    private Servings $servings;

    #[Embedded]
    private Seasonality $seasonality;

    #[Column(type: IngredientsType::NAME)]
    private array $ingredients;

    /**
     * @param iterable<Ingredient> $ingredients
     */
    public function __construct(
        string $name,
        Servings $servings,
        Seasonality $seasonality,
        array $ingredients,
    ) {
        $this->name = $name;
        $this->servings = $servings;
        $this->seasonality = $seasonality;
        $this->ingredients = $ingredients;
    }

    public function id(): RecipeId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function servings(): Servings
    {
        return $this->servings;
    }

    /**
     * @return iterable<Ingredient>
     */
    public function ingredients(): iterable
    {
        return $this->ingredients;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
