<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;
use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Meal
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ManyToOne(inversedBy: "meals")]
    private Day $day;

    #[Column("recipe_id", RecipeIdType::NAME)]
    private RecipeId $recipe;

    #[Column("remains_of_id", type: Types::INTEGER)]
    private ?int $remainsOf = null;

    public function __construct(Day $day, Recipe $recipe)
    {
        $this->day = $day;
        $this->recipe = $recipe->id();
    }

    public function recipe(): RecipeId
    {
        return $this->recipe;
    }

    public function isRemains(): bool
    {
        return $this->remainsOf !== null;
    }
}
