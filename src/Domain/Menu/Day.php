<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity]
class Day
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ManyToOne(inversedBy: "days")]
    private Menu $menu;

    #[Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $date;

    #[OneToMany(Meal::class, "day", ["PERSIST"])]
    private Collection $meals;

    public function __construct(Menu $menu, DateTimeInterface $date)
    {
        $this->menu = $menu;
        $this->date = $date;
        $this->meals = new ArrayCollection();
    }

    public function date(): DateTimeInterface
    {
        return $this->date;
    }

    public function dayOfWeek(): DayOfWeek
    {
        return DayOfWeek::of($this->date());
    }

    /**
     * @return iterable<Meal>
     */
    public function meals(): iterable
    {
        return $this->meals;
    }

    public function planMeal(Recipe|Remains $meal): void
    {
        $this->meals->add(
            $meal instanceof Recipe
                ? Meal::fromRecipe($meal, $this)
                : $meal->toMeal($this),
        );
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
