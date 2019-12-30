<?php

namespace PhpBench\Extension\Sampler;

use PhpBench\Bridge\Php\Sampler\CallbackSampler;
use PhpBench\Extension\Sampler\Command\SampleCommand;
use PhpBench\Library\Sampler\SamplerLocator;
use PhpBench\Library\Sampler\Sampler\ConstantSampler;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;

class SamplerExtension implements Extension
{
    const TAG_SAMPLER = 'sampler';

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
        $container->register(SampleCommand::class, function (Container $container) {
            return new SampleCommand(
                $container->get(SamplerLocator::class)
            );
        }, [
            ConsoleExtension::TAG_COMMAND => [
                'name' => 'sample'
            ],
        ]);

        $container->register(SamplerLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_SAMPLER) as $serviceId => $params) {
                $defintions[] = SamplerDefinition::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazySamplerLocator($container, ...$defintions);
        });

        $container->register(CallbackSampler::class, function (Container $container) {
            return new CallbackSampler();
        }, [
            self::TAG_SAMPLER => [
                'alias' => 'callback',
            ],
        ]);

        $container->register(CallbackSampler::class, function (Container $container) {
            return new ConstantSampler();
        }, [
            self::TAG_SAMPLER => [
                'alias' => 'constant',
            ],
        ]);
    }
}
