<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use App\Infrastructure\Menu\MenuIdType;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
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
    #[Id, GeneratedValue, Column(type: MenuIdType::NAME)]
    private ?MenuId $id = null;

    #[OrderBy(["date" => "ASC"]), OneToMany(Day::class, "menu", ["PERSIST"])]
    private Collection $days;

    /**
     * @var Remains[]
     */
    private array $remains = [];

    /**
     * @param Remains[] $remains
     */
    public function __construct(DateTimeInterface $monday, array $remains = [])
    {
        $this->days = new ArrayCollection();
        $date = DatePoint::createFromInterface($monday);
        foreach (DayOfWeek::cases() as $day) {
            $this->days->add(new Day($this, $day->adjustInto($date)));
        }
        $this->remains = $remains;
    }

    public function id(): MenuId
    {
        return $this->id;
    }

    public function monday(): DateTimeInterface
    {
        return $this->days()->first()->date();
    }

    /**
     * @return ReadableCollection<int, Day>
     */
    public function days(): ReadableCollection
    {
        return $this->days;
    }

    public function planMeal(DayOfWeek $day, Recipe|Remains $meal): void
    {
        $this->day($day)->planMeal($meal);
    }

    public function day(DayOfWeek $day): Day
    {
        return $this->days()->findFirst(
            fn(int $_, Day $value): bool => $value->dayOfWeek() === $day,
        );
    }

    public function clear(DayOfWeek $day): void
    {
        foreach ($this->day($day)->meals() as $meal) {
            if ($meal->isRemains()) {
                $this->putBack($meal);
            }
        }
        $this->day($day)->clear();
    }

    private function putBack(Meal $remains): void
    {
        foreach ($this->days() as $day) {
            foreach ($day->meals() as $meal) {
                if ($remains->isRemainsOf($meal)) {
                    $meal->putBack($remains->servings());
                    return;
                }
            }
        }
    }

    /**
     * @return Remains[]
     */
    public function remains(DayOfWeek $before = DayOfWeek::SUNDAY): array
    {
        $remains = [];
        foreach ($this->days() as $day) {
            if ($day->dayOfWeek()->value <= $before->value) {
                foreach ($day->meals() as $meal) {
                    if ($meal->hasRemains()) {
                        $remains[] = $meal;
                    }
                }
            }
        }
        return $remains;
    }

    public function takeRemains(DayOfWeek $day): ?Remains
    {
        $remains = array_shift($this->remains);
        if ($remains) {
            return $remains;
        } else {
            $remains = $this->remains($day);
            return count($remains) === 0 ? null : reset($remains);
        }
    }

    /**
     * @return iterable<Grocery>
     */
    public function groceries(): iterable
    {
        $days = $this->days()
            ->map(fn(Day $day): array => $day->groceries())
            ->toArray();
        $groceries = array_merge(...$days);
        usort(
            $groceries,
            fn(Grocery $a, Grocery $b): int => $a->name() <=> $b->name(),
        );
        return $groceries;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
