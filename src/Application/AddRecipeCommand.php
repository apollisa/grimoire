<?php

namespace App\Application;

class AddRecipeCommand
{
    public int $folder;
    public string $name;
    public int $servings;
    public ?int $starts = null;
    public ?int $ends = null;

    /** @var string[] */
    public array $ingredients = [];

    /** @var string[] */
    public array $instructions = [];
}
