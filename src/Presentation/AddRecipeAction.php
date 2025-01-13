<?php

namespace App\Presentation;

use App\Application\AddRecipeCommand;
use App\Application\RecipeAdder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
#[Route("/recettes/nouvelle", "recipe_add", methods: ["GET", "POST"])]
class AddRecipeAction extends FragmentAction
{
    public function __construct(
        private readonly FormFactoryInterface $factory,
        private readonly UrlGeneratorInterface $generator,
        private readonly RecipeAdder $adder,
        RequestStack $stack,
        Environment $twig,
    ) {
        parent::__construct($stack, $twig);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function __invoke(
        Request $request,
        #[MapQueryParameter("dossier")] ?int $folder = null,
    ): Response {
        $command = $this->getCommand($folder);
        $form = $this->factory->create(RecipeType::class, $command, [
            "action" => $this->generator->generate("recipe_add"),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $id = $this->adder->add($command)->id()->value();
            $url = $this->generator->generate("recipe_display", ["id" => $id]);
            return new RedirectResponse($url, Response::HTTP_SEE_OTHER);
        } else {
            $parameters = ["form" => $form->createView()];
            return $this->render("recipes/new.html.twig", $parameters);
        }
    }

    private function getCommand(?int $folder): AddRecipeCommand
    {
        $command = new AddRecipeCommand();
        if ($folder !== null) {
            $command->folder = $folder;
        }
        return $command;
    }
}
