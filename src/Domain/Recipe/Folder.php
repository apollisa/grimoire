<?php

namespace App\Domain\Recipe;

use App\Infrastructure\Recipe\FolderIdType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Folder
{
    #[Id, GeneratedValue, Column(type: FolderIdType::NAME)]
    private ?FolderId $id = null;

    #[Column]
    private string $name;

    #[Column(type: Types::BOOLEAN)]
    private bool $isIncludedInMenus;

    public function __construct(string $name, bool $isIncludedInMenus = false)
    {
        $this->name = $name;
        $this->isIncludedInMenus = $isIncludedInMenus;
    }

    public function id(): FolderId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isIncludedInMenus(): bool
    {
        return $this->isIncludedInMenus;
    }
}
