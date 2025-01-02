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
    private readonly ReflectionProperty $quantity;
    private readonly ReflectionProperty $value;

    public function __construct()
    {
        $this->quantity = new ReflectionProperty(Grocery::class, "quantity");
        $this->value = new ReflectionProperty(Quantity::class, "value");
    }

    public function __invoke(Grocery $entity): void
    {
        if (!$this->value->isInitialized($entity->quantity())) {
            $this->quantity->setValue($entity, null);
        }
    }
}