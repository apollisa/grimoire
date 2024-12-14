<?php

namespace App\Domain\Menu;

use App\Domain\Recipe\Recipe;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
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

    /**
     * @var Remains[]
     */
    private array $remains;

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
        $this->days()
            ->findFirst(
                fn(int $_, Day $value): bool => $value->dayOfWeek() === $day,
            )
            ?->planMeal($meal);
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

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }
}
