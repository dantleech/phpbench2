<?php

namespace PhpBench\Extension\DataStructure;

use PhpBench\Library\DataStructure\DataFactory;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;

class DataStructureExtension implements Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register(DataFactory::class, function (Container $container) {
            return new DataFactory();
        });
    }
    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
    }
}
