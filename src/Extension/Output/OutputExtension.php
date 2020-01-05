<?php

namespace PhpBench\Extension\Output;

use PhpBench\Bridge\Extension\AliasedService;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Output\OutputLocator;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;
use PhpBench\Extension\Output\LazyOutputLocator;

class OutputExtension implements Extension
{
    const TAG_OUTPUT = 'output';

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register(OutputLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_OUTPUT) as $serviceId => $params) {
                $defintions[] = AliasedService::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyOutputLocator($container, ...$defintions);

        });
    }
    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
    }
}
