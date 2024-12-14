<?php

namespace App\Domain\Recipe;

use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\ORM\Mapping\Column;
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

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function id(): RecipeId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
