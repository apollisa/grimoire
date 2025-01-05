<?php

namespace App\Presentation;

use App\Domain\Recipe\Month;
use Closure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UnitEnum;

class MonthType extends AbstractType
{
    private readonly Closure $labeller;
    private readonly array $choices;

    public function __construct(#[Autowire(param: "app.months")] array $months)
    {
        $this->labeller = fn(int $month): string => $months[$month];
        $this->choices = array_map(
            fn(UnitEnum $month): int => $month->value,
            Month::cases(),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "choice_label" => $this->labeller,
            "choices" => $this->choices,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
