<?php

namespace App\Tests\Fixtures;

use App\Domain\Menu\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Clock\DatePoint;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Menu(new DatePoint("2024-08-26")));
        $manager->flush();
    }
}
