<?php

namespace PhpBench\Extension\Output;

use PhpBench\Bridge\Extension\AliasedService;
use PhpBench\Bridge\Php\Stream\AnsiOutput;
use PhpBench\Bridge\Php\Stream\StreamInputOuput;
use PhpBench\Library\Output\OutputLocator;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\MapResolver\Resolver;

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

        $container->register('output.'.StreamInputOuput::class, function (Container $container) {
            return new StreamInputOuput();
        }, [
            self::TAG_OUTPUT => [
                'alias' => 'stream',
            ],
        ]);

        $container->register(AnsiOutput::class, function (Container $container) {
            return new AnsiOutput($container->get('output.'.StreamInputOuput::class));
        }, [
            self::TAG_OUTPUT => [
                'alias' => 'ansi',
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
