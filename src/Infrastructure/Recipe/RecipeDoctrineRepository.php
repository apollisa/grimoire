<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\Folder;
use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\Month;
use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeDoctrineRepository extends ServiceEntityRepository implements
    RecipeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function ofId(RecipeId $id): Recipe
    {
        return $this->find($id);
    }

    public function ofFolder(Folder $folder): iterable
    {
        return $this->findBy(["folder" => $folder->id()], ["name" => "ASC"]);
    }

    public function inFolderAndMonth(array $folders, Month $month): array
    {
        $ids = array_map(
            fn(Folder $folder): FolderId => $folder->id(),
            $folders,
        );
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App\Domain\Recipe\Recipe r
            WHERE r.folder IN (:folders)
              AND (
                :month BETWEEN COALESCE(r.seasonality.starts, 1) AND COALESCE(r.seasonality.ends, 12)
                OR r.seasonality.starts > r.seasonality.ends AND (:month >= r.seasonality.starts OR :month <= r.seasonality.ends)
              )",
            )
            ->setParameter("folders", $ids)
            ->setParameter("month", $month)
            ->getResult();
    }

    public function all(): iterable
    {
        return $this->findBy([], ["name" => "ASC"]);
    }

    public function add(Recipe $recipe): Recipe
    {
        $this->getEntityManager()->persist($recipe);
        return $recipe;
    }
}
