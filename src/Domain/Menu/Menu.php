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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Clock\DatePoint;

#[Entity]
class Menu
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[OrderBy(["date" => "ASC"]), OneToMany(Day::class, "menu", ["PERSIST"])]
    private Collection $days;

    public function __construct(DateTimeInterface $monday)
    {
        $this->days = new ArrayCollection();
        $date = DatePoint::createFromInterface($monday);
        for ($day = 0; $day < 7; $day++) {
            $this->days->add(new Day($this, $date->modify("+$day day")));
        }
    }

    /**
     * @return iterable<Day>
     */
    public function days(): iterable
    {
        return $this->days;
    }

    public function planMeal(DayOfWeek $day, Recipe $recipe): void
    {
        $this->days->get($day->getValue() - 1)->planMeal($recipe);
    }
}
