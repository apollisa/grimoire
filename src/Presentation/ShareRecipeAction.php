<?php

namespace App\Presentation;

use App\Application\RecipeSharer;
use App\Domain\Recipe\RecipeId;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

#[Route("/recettes/{id}", "recipe_share", methods: Request::METHOD_POST)]
class ShareRecipeAction extends AbstractController
{
    public function __construct(private readonly RecipeSharer $sharer) {}

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(int $id, Request $request): Response
    {
        $url = $this->generateUrl(
            "recipe_public",
            ["slug" => $this->sharer->share(new RecipeId($id))],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        return $this->redirect($url, Response::HTTP_SEE_OTHER);
    }
}
