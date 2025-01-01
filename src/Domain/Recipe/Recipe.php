<?php

namespace App\Domain\Recipe;

use App\Infrastructure\Recipe\FolderIdType;
use App\Infrastructure\Recipe\IngredientsType;
use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\DBAL\Types\Types;
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

    #[Column("folder_id", FolderIdType::NAME)]
    private FolderId $folder;

    #[Column]
    private string $name;

    #[Embedded(columnPrefix: false)]
    private Servings $servings;

    #[Embedded]
    private Seasonality $seasonality;

    #[Column(type: IngredientsType::NAME)]
    private array $ingredients;

    #[Column(type: Types::JSON)]
    private readonly array $instructions;

    /**
     * @param iterable<Ingredient> $ingredients
     * @param iterable<string> $instructions
     */
    public function __construct(
        Folder $folder,
        string $name,
        Servings $servings,
        Seasonality $seasonality,
        array $ingredients,
        array $instructions,
    ) {
        $this->folder = $folder->id();
        $this->name = $name;
        $this->servings = $servings;
        $this->seasonality = $seasonality;
        $this->ingredients = $ingredients;
        $this->instructions = $instructions;
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

    /**
     * @return iterable<string>
     */
    public function instructions(): iterable
    {
        return $this->instructions;
    }

    public function equals(self $other): bool
    {
        return $this->id == $other->id;
    }
}
