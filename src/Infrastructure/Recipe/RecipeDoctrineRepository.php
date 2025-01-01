<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\Folder;
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

    public function ofMonth(Month $month): array
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App\Domain\Recipe\Recipe r
            WHERE :month BETWEEN r.seasonality.starts AND r.seasonality.ends
               OR r.seasonality.starts > r.seasonality.ends AND (:month >= r.seasonality.starts OR :month <= r.seasonality.ends)",
            )
            ->setParameter("month", $month)
            ->getResult();
    }

    public function all(): iterable
    {
        return $this->findBy([], ["name" => "ASC"]);
    }
}
