<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\FolderId;
use App\Infrastructure\Shared\IdType;

class FolderIdType extends IdType
{
    public const NAME = "folder_id";

    protected function getIdClass(): string
    {
        return FolderId::class;
    }
}
