<?php

namespace App\Tests\Domain\Recipe;

use App\Domain\Recipe\RecipeId;
use PHPUnit\Framework\TestCase;

class RecipeIdTest extends TestCase
{
    public function testTwoDifferentInstancesAreEqual()
    {
        self::assertEquals(new RecipeId(1), new RecipeId(1));
    }
}
