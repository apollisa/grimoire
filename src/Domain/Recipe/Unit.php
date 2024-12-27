<?php

namespace App\Domain\Recipe;

enum Unit: string
{
    case CENTILITRES = "cL";
    case DECILITRES = "dL";
    case GRAMS = "g";
    case KILOGRAMS = "kg";
    case MILLILITRES = "mL";
    case PINCH = "pinch";
    case TBSP = "tbsp.";
    case TSP = "tsp.";
    case UNITS = "";
}
