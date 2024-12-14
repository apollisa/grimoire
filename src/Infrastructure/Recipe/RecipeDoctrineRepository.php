<?php

namespace App\Infrastructure\Recipe;

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
}
