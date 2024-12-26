<?php

namespace App\Tests\Domain\Menu;

use App\Domain\Menu\MenuFactory;
use App\Domain\Menu\MenuFiller;
use App\Domain\Menu\MenuRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Clock\MockClock;

class MenuFactoryTest extends TestCase
{
    private MenuFactory $factory;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $clock = new MockClock("2024-12-16");
        $repository = self::createStub(MenuRepository::class);
        $filler = self::createStub(MenuFiller::class);
        $this->factory = new MenuFactory($clock, $repository, $filler);
    }

    public function testCreatesNextMonday(): void
    {
        $menu = $this->factory->create();

        self::assertEquals(new DatePoint("2024-12-23"), $menu->monday());
    }
}
