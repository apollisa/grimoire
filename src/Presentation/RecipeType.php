<?php

namespace App\Presentation;

use App\Domain\Recipe\Folder;
use App\Domain\Recipe\FolderId;
use App\Domain\Recipe\FolderRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RecipeType extends AbstractType
{
    private readonly DataTransformerInterface $transformer;

    public function __construct(private readonly FolderRepository $repository)
    {
        $this->transformer = new CallbackTransformer(
            fn(array $value): string => join('\n', $value),
            fn(?string $value): array => preg_split(
                "/\R+/",
                $value ?? "",
                flags: PREG_SPLIT_NO_EMPTY,
            ),
        );
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add($this->getFolderField($builder))
            ->add("name", TextType::class, ["label" => "Nom"])
            ->add("servings", IntegerType::class, [
                "attr" => ["min" => 1],
                "label" => "Parts",
            ])
            ->add("starts", MonthType::class, [
                "label" => "De",
                "required" => false,
            ])
            ->add("ends", MonthType::class, [
                "label" => "À",
                "required" => false,
            ])
            ->add($this->getIngredientField($builder))
            ->add($this->getInstructionField($builder));
    }

    private function getFolderField(
        FormBuilderInterface $builder,
    ): FormBuilderInterface {
        $transformer = new CallbackTransformer(
            fn(?int $id): ?Folder => $id === null
                ? null
                : $this->repository->ofId(new FolderId($id)),
            fn(Folder $folder): int => $folder->id()->value(),
        );
        return $builder
            ->create("folder", EntityType::class, [
                "choice_label" => "name",
                "class" => Folder::class,
                "label" => "Dossier",
            ])
            ->addModelTransformer($transformer);
    }

    private function getIngredientField(
        FormBuilderInterface $builder,
    ): FormBuilderInterface {
        return $builder
            ->create("ingredients", TextareaType::class, [
                "attr" => ["placeholder" => "1 kg patates"],
                "help" => "Un ingrédient par ligne, sans article",
                "label" => "Ingrédients",
            ])
            ->addModelTransformer($this->transformer);
    }

    private function getInstructionField(
        FormBuilderInterface $builder,
    ): FormBuilderInterface {
        return $builder
            ->create("instructions", TextareaType::class, [
                "help" => "Une instruction par ligne",
                "required" => false,
            ])
            ->addModelTransformer($this->transformer);
    }
}
