<?php

namespace App\Tests\Fixtures;

use App\Domain\Recipe\Folder;
use App\Domain\Recipe\FolderId;

class TestFolder extends Folder
{
    public function __construct()
    {
        parent::__construct("Plats");
    }

    public function id(): FolderId
    {
        return new FolderId(1);
    }
}
