<?php

namespace PhpBench\Extension\Visualize;

use PhpBench\Bridge\Php\Sampler\CallbackSampler;
use PhpBench\Bridge\Php\Visualizer\AnsiBarChartVisualizer;
use PhpBench\Extension\Sampler\Command\SampleCommand;
use PhpBench\Extension\Visualize\Command\VisualizeCommand;
use PhpBench\Library\Sampler\SamplerLocator;
use PhpBench\Library\Visualize\VisualizerLocator;
use PhpBench\Library\Visualize\Visualizer\TestVisualizer;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;
use PhpBench\Extension\Visualize\LazyVisualizerLocator;
use PhpBench\Extension\Visualize\VisualizerDefinition;

class VisualizerExtension implements Extension
{
    const TAG_VISUALIZER = 'visualizer';

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
        $container->register(VisualizeCommand::class, function (Container $container) {
            return new VisualizeCommand(
                $container->get(VisualizerLocator::class)
            );
        }, [
            ConsoleExtension::TAG_COMMAND => [
                'name' => 'visualize'
            ],
        ]);

        $container->register(VisualizerLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_VISUALIZER) as $serviceId => $params) {
                $defintions[] = VisualizerDefinition::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyVisualizerLocator($container, ...$defintions);
        });

        $container->register(AnsiBarChartVisualizer::class, function (Container $contianer) {
            return new AnsiBarChartVisualizer();
        }, [
            self::TAG_VISUALIZER => [
                'alias' => 'bar',
            ],
        ]);

        $container->register(TestVisualizer::class, function (Container $contianer) {
            return new TestVisualizer();
        }, [
            self::TAG_VISUALIZER => [
                'alias' => 'test',
            ],
        ]);
    }
}
