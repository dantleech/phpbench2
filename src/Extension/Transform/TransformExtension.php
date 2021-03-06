<?php

namespace PhpBench\Extension\Transform;

use PhpBench\Bridge\Extension\AliasedService;
use PhpBench\Bridge\MathPhp\Transform\DescribeTransformer;
use PhpBench\Bridge\MathPhp\Transform\KernelDensityTransformer;
use PhpBench\Bridge\Php\Transform\AggregateValueTransformer;
use PhpBench\Extension\Transform\Command\TransformCommand;
use PhpBench\Library\Transform\TransformerLocator;
use PhpBench\Library\Transform\Transformer\TestTransformer;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;

class TransformExtension implements Extension
{
    const TAG_TRANSFORMER = 'transformer';

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
                $defintions[] = AliasedService::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyTransformerLocator($container, ...$defintions);
        });

        $container->register(KernelDensityTransformer::class, function (Container $container) {
            return new KernelDensityTransformer();
        }, [
            self::TAG_TRANSFORMER => [
                'alias' => 'kde',
            ],
        ]);

        $container->register(DescribeTransformer::class, function (Container $container) {
            return new DescribeTransformer();
        }, [
            self::TAG_TRANSFORMER => [
                'alias' => 'describe',
            ],
        ]);

        $container->register(AggregateValueTransformer::class, function (Container $container) {
            return new AggregateValueTransformer();
        }, [
            self::TAG_TRANSFORMER => [
                'alias' => 'aggregate_value',
            ],
        ]);

        $container->register(TestTransformer::class, function (Container $container) {
            return new TestTransformer();
        }, [
            self::TAG_TRANSFORMER => [
                'alias' => 'test',
            ],
        ]);
    }
}
