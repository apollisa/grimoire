<?php

namespace App\Domain\Shared;

enum Unit: string
{
    case CENTILITRES = "cL";
    case DECILITRES = "dL";
    case GRAMS = "g";
    case KILOGRAMS = "kg";
    case LITRES = "L";
    case MILLILITRES = "mL";
    case PINCH = "pinch";
    case TBSP = "tbsp.";
    case TSP = "tsp.";
    case UNITS = "";
}
