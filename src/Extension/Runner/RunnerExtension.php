<?php

namespace PhpBench\Extension\Runner;

use PhpBench\Extension\Runner\Command\RunCommand;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;

class RunnerExtension implements Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register(RunCommand::class, function (Container $container) {
            return new RunCommand();
        }, [
            ConsoleExtension::TAG_COMMAND => [
                'name' => 'run',
            ],
        ]);
    }
    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
    }
}
