<?php

namespace App\Presentation;

use App\Application\AddRecipeCommand;
use App\Application\RecipeAdder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[
    Route(
        "/recettes/nouvelle",
        "recipe_add",
        methods: [Request::METHOD_GET, Request::METHOD_POST],
    ),
]
class AddRecipeAction extends AbstractController
{
    use StimulusRequestTrait;

    private const TEMPLATE = "recipes/new.html.twig";

    public function __construct(private readonly RecipeAdder $adder) {}

    public function __invoke(
        Request $request,
        #[MapQueryParameter("dossier")] ?int $folder = null,
    ): Response {
        $command = $this->getCommand($folder);
        $form = $this->createForm(RecipeType::class, $command, [
            "action" => $this->generateUrl("recipe_add"),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $id = $this->adder->add($command)->id()->value();
            return $this->redirectToRoute(
                "recipe_display",
                ["id" => $id],
                Response::HTTP_SEE_OTHER,
            );
        } else {
            $isStimulusRequest = $this->isStimulusRequest($request);
            $parameters = [
                "fragment" => $isStimulusRequest,
                "form" => $form->createView(),
            ];
            return $isStimulusRequest
                ? $this->renderBlock(self::TEMPLATE, "content", $parameters)
                : $this->render(self::TEMPLATE, $parameters);
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
