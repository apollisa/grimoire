<?php

namespace App\Application;

use App\Domain\Recipe\Month;

class AddRecipeCommand
{
    public int $folder;
    public string $name;
    public int $servings;
    public int $starts = Month::JANUARY->value;
    public int $ends = Month::DECEMBER->value;

    /** @var string[] */
    public array $ingredients = [];

    /** @var string[] */
    public array $instructions = [];
}
