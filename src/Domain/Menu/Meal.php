<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\Servings;
use App\Infrastructure\Menu\MealIdType;
use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Meal implements Remains
{
    #[Id, GeneratedValue, Column(type: MealIdType::NAME)]
    private ?MealId $id = null;

    #[ManyToOne(inversedBy: "meals")]
    private Day $day;

    #[Column("recipe_id", RecipeIdType::NAME)]
    private RecipeId $recipe;

    #[Embedded(columnPrefix: "remaining_")]
    private Servings $remains;

    #[ManyToOne, JoinColumn("remains_of_id")]
    private ?Meal $remainsOf = null;

    public static function fromRecipe(Recipe $recipe, Day $day): self
    {
        $meal = new self($day, $recipe->id());
        $meal->remains = $recipe->servings()->minus($meal->servings());
        return $meal;
    }

    public static function fromRemains(Remains $remains, Day $day): self
    {
        $meal = new self($day, $remains->recipe());
        $meal->remainsOf = $remains->meal();
        return $meal;
    }

    private function __construct(Day $day, ?RecipeId $recipe)
    {
        $this->day = $day;
        $this->recipe = $recipe;
        $this->remains = Servings::zero();
    }

    public function id(): MealId
    {
        return $this->id;
    }

    public function meal(): Meal
    {
        return $this;
    }

    public function recipe(): RecipeId
    {
        return $this->recipe;
    }

    public function servings(): Servings
    {
        return new Servings(2);
    }

    public function hasRemains(): bool
    {
        return $this->remains->isMoreThan(Servings::zero());
    }

    public function putBack(Servings $servings): void
    {
        $this->remains = $this->remains->plus($servings);
    }

    public function toMeal(Day $day): Meal
    {
        $meal = self::fromRemains($this, $day);
        $this->remains = $this->remains->minus($meal->servings());
        return $meal;
    }

    public function isRemains(): bool
    {
        return $this->remainsOf !== null;
    }

    public function isRemainsOf(Meal $meal): bool
    {
        return $this->remainsOf?->equals($meal);
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
