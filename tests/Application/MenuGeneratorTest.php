<?php

namespace App\Tests\Application;

use App\Application\MenuGenerator;
use App\Domain\Menu\Menu;
use App\Domain\Menu\MenuFactory;
use App\Domain\Menu\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

class MenuGeneratorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGeneratesMenu(): void
    {
        $manager = self::createStub(EntityManagerInterface::class);
        $manager
            ->method("wrapInTransaction")
            ->willReturnCallback(fn($argument) => $argument());
        $factory = self::createStub(MenuFactory::class);
        $repository = self::createMock(MenuRepository::class);
        $menu = new Menu(new DatePoint());
        $factory->method("create")->willReturn($menu);
        $generator = new MenuGenerator($manager, $factory, $repository);

        $repository->expects(self::once())->method("add")->with($menu);

        $generator->generate();
    }
}
