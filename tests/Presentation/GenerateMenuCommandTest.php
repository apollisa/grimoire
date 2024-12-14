<?php

namespace App\Tests\Presentation;

use App\Domain\Menu\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateMenuCommandTest extends KernelTestCase
{
    use ClockSensitiveTrait;

    public function testGeneratesMenu(): void
    {
        self::mockTime("2024-08-23");
        $application = new Application(self::bootKernel());
        $command = $application->find("app:generate-menu");
        $tester = new CommandTester($command);

        $tester->execute([]);

        $repository = self::getContainer()->get(MenuRepository::class);
        self::assertCount(2, $repository->upcoming());
    }
}
