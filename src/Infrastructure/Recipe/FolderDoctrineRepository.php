<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\Folder;
use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FolderDoctrineRepository extends ServiceEntityRepository implements
    FolderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    public function ofId(FolderId $id): Folder
    {
        return $this->find($id);
    }

    public function all(): iterable
    {
        return $this->findBy([], ["name" => "ASC"]);
    }

    public function includedInMenus(): array
    {
        return $this->findBy(["isIncludedInMenus" => true]);
    }
}
