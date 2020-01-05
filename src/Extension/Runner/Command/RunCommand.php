<?php

namespace PhpBench\Extension\Runner\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    const ARG_NAME = 'path';

    protected function configure()
    {
        $this->addArgument(self::ARG_NAME, InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
