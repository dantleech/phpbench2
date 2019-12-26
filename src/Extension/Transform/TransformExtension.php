<?php

namespace PhpBench\Extension\Transform;

use PhpBench\Extension\Transform\Command\TransformCommand;
use PhpBench\Library\Transform\TransformLocator;
use PhpBench\Library\Transform\TransformerLocator;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;
use PhpBench\Extension\Transform\LazyTransformLocator;
use PhpBench\Extension\Transform\TransformerDefinition;

class TransformExtension implements Extension
{
    const TAG_TRANSFORMER = 'sampler';

    /**
     * {@inheritDoc}
     */
    public function configure(Resolver $schema)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->register(TransformCommand::class, function (Container $container) {
            return new TransformCommand(
                $container->get(TransformerLocator::class)
            );
        }, [
            ConsoleExtension::TAG_COMMAND => [
                'name' => 'transform'
            ],
        ]);

        $container->register(TransformerLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_TRANSFORMER) as $serviceId => $params) {
                $defintions[] = TransformerDefinition::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyTransformLocator($container, ...$defintions);
        });
    }
}