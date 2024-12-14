<?php

namespace App\Presentation;

use App\Application\MenuGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand("app:generate-menu")]
class GenerateMenuCommand extends Command
{
    public function __construct(private readonly MenuGenerator $generator)
    {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $this->generator->generate();
        return Command::SUCCESS;
    }
}
