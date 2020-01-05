<?php

namespace PhpBench\Extension\Visualize;

use PhpBench\Bridge\Php\Visualizer\AnsiBarChartRenderer;
use PhpBench\Extension\Visualize\Command\VisualizeCommand;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Output\OutputLocator;
use PhpBench\Library\Visualize\RendererLocator;
use PhpBench\Library\Visualize\Renderer\TestRenderer;
use PhpBench\Library\Visualize\Visualizer;
use Phpactor\Container\Container;
use Phpactor\Container\ContainerBuilder;
use Phpactor\Container\Extension;
use Phpactor\Extension\Console\ConsoleExtension;
use Phpactor\MapResolver\Resolver;

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
                $container->get(RendererLocator::class),
                $container->get(Visualizer::class)
            );
        }, [
            ConsoleExtension::TAG_COMMAND => [
                'name' => 'visualize'
            ],
        ]);

        $container->register(Visualizer::class, function (Container $container) {
            return new Visualizer(
                $container->get(RendererLocator::class),
                $container->get(InputLocator::class),
                $container->get(OutputLocator::class)
            );
        });

        $container->register(RendererLocator::class, function (Container $container) {
            $defintions = [];
            foreach ($container->getServiceIdsForTag(self::TAG_VISUALIZER) as $serviceId => $params) {
                $defintions[] = VisualizerDefinition::fromArray(array_merge([
                    'serviceId' => $serviceId,
                ], $params));
            }

            return new LazyVisualizerLocator($container, ...$defintions);
        });

        $container->register(AnsiBarChartRenderer::class, function (Container $contianer) {
            return new AnsiBarChartRenderer();
        }, [
            self::TAG_VISUALIZER => [
                'alias' => 'bar',
            ],
        ]);

        $container->register(TestRenderer::class, function (Container $contianer) {
            return new TestRenderer();
        }, [
            self::TAG_VISUALIZER => [
                'alias' => 'test',
            ],
        ]);
    }
}
