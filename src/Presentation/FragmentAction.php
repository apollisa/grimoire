<?php

namespace App\Presentation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class FragmentAction
{
    use StimulusRequestTrait;

    protected function __construct(
        private readonly RequestStack $stack,
        private readonly Environment $twig,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function render(string $template, array $parameters): Response
    {
        $request = $this->stack->getCurrentRequest();
        $isStimulusRequest = $this->isStimulusRequest($request);
        $wrapper = $this->twig->load($template);
        $parameters["fragment"] = $isStimulusRequest;
        $content = $isStimulusRequest
            ? $wrapper->renderBlock("content", $parameters)
            : $wrapper->render($parameters);
        return new Response($content);
    }
}
