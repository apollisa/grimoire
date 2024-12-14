<?php

namespace App\Infrastructure\Menu;

use App\Domain\Menu\Menu;
use App\Domain\Menu\MenuRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Clock\ClockInterface;

class MenuDoctrineRepository extends ServiceEntityRepository implements
    MenuRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly ClockInterface $clock,
    ) {
        parent::__construct($registry, Menu::class);
    }

    public function last(): ?Menu
    {
        return $this->findOneBy([], ["id" => "DESC"]);
    }

    public function upcoming(): iterable
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT m, d FROM App\Domain\Menu\Menu m JOIN m.days d WHERE d.date >= :date",
            )
            ->setParameter("date", $this->clock->now(), Types::DATE_IMMUTABLE)
            ->getResult();
    }

    public function add(Menu $menu): void
    {
        $this->getEntityManager()->persist($menu);
    }
}
