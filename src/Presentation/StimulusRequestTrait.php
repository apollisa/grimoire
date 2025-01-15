<?php

namespace App\Presentation;

use Symfony\Component\HttpFoundation\Request;

trait StimulusRequestTrait
{
    private function isStimulusRequest(Request $request): bool
    {
        return $request->headers->get("X-Stimulus") === "true";
    }
}
