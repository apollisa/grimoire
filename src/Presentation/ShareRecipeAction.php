<?php

namespace App\Presentation;

use App\Application\RecipeSharer;
use App\Domain\Recipe\RecipeId;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

#[AsController, Route("/recettes/{id}", "recipe_share", methods: "POST")]
class ShareRecipeAction
{
    public function __construct(
        private readonly UrlGeneratorInterface $generator,
        private readonly RecipeSharer $sharer,
    ) {
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(int $id, Request $request): Response
    {
        $url = $this->generator->generate(
            "recipe_public",
            ["slug" => $this->sharer->share(new RecipeId($id))],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        return new RedirectResponse($url, Response::HTTP_SEE_OTHER);
    }
}
