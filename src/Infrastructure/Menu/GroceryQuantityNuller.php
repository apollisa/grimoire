<?php

namespace App\Infrastructure\Menu;

use App\Domain\Menu\Grocery;
use App\Domain\Shared\Quantity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use ReflectionProperty;

#[AsEntityListener(Events::postLoad, entity: Grocery::class)]
class GroceryQuantityNuller
{
    private readonly ReflectionProperty $property;
    private readonly ReflectionProperty $value;

    public function __construct()
    {
        $this->property = new ReflectionProperty(Grocery::class, "quantity");
        $this->value = new ReflectionProperty(Quantity::class, "value");
    }

    public function __invoke(Grocery $entity): void
    {
        if (!$this->value->isInitialized($entity->quantity())) {
            $this->property->setValue($entity, null);
        }
    }
}
