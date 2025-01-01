<?php

namespace App\Domain\Recipe;

interface FolderRepository
{
    public function ofId(FolderId $id): Folder;

    /**
     * @return iterable<Folder>
     */
    public function all(): iterable;
}
