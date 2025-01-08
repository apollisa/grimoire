<?php

namespace App\Infrastructure\Recipe;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\Seasonality;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use ReflectionProperty;

#[AsEntityListener(Events::postLoad, entity: Recipe::class)]
class RecipeSeasonalityNuller
{
    private readonly ReflectionProperty $property;
    private readonly ReflectionProperty $starts;
    private readonly ReflectionProperty $ends;

    public function __construct()
    {
        $this->property = new ReflectionProperty(Recipe::class, "seasonality");
        $this->starts = new ReflectionProperty(Seasonality::class, "starts");
        $this->ends = new ReflectionProperty(Seasonality::class, "ends");
    }

    public function __invoke(Recipe $entity): void
    {
        $seasonality = $entity->seasonality();
        $isStartInitialized = $this->starts->isInitialized($seasonality);
        $isEndsInitialized = $this->ends->isInitialized($seasonality);
        if (!$isStartInitialized || !$isEndsInitialized) {
            $this->property->setValue($entity, null);
        }
    }
}
