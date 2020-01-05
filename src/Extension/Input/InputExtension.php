<?php

namespace PhpBench\Extension\Input;

use PhpBench\Bridge\Extension\AliasedService;
use PhpBench\Bridge\Php\Stream\StreamInputOuput;
use PhpBench\Library\Input\InputLocator;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;

class InputExtension implements Extension
{
    const TAG_INPUT = 'input';

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register(InputLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_INPUT) as $serviceId => $params) {
                $defintions[] = AliasedService::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyInputLocator($container, ...$defintions);
        });

        $container->register(StreamInputOuput::class, function (Container $container) {
            return new StreamInputOuput();
        }, [
            self::TAG_INPUT => [
                'alias' => 'stream',
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
