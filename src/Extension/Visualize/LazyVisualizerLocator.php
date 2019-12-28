<?php

namespace PhpBench\Extension\Visualize;

use PhpBench\Library\Sampler\Exception\SamplerNotFound;
use PhpBench\Library\Sampler\Sampler;
use PhpBench\Library\Sampler\SamplerLocator;
use PhpBench\Library\Visualize\Exception\VisualizerNotFound;
use PhpBench\Library\Visualize\Visualizer;
use PhpBench\Library\Visualize\VisualizerLocator;
use Psr\Container\ContainerInterface;
use PhpBench\Extension\Visualize\VisualizerDefinition;

class LazyVisualizerLocator implements VisualizerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var VisualizerDefinition[]
     */
    private $definitions = [];

    public function __construct(ContainerInterface $container, VisualizerDefinition ...$definitions)
    {
        $this->container = $container;
        array_map(function (VisualizerDefinition $definition) {
            $this->definitions[$definition->alias()] = $definition;
        }, $definitions);
    }

    public function get(string $alias): Visualizer
    {
        return $this->container->get($this->getDefinition($alias)->serviceId());
    }

    private function getDefinition(string $alias): VisualizerDefinition
    {
        if (!isset($this->definitions[$alias])) {
            throw new VisualizerNotFound(sprintf(
                'Visualizer with alias "%s" not found, known visualizer alises: "%s"',
                $alias, implode('", "', array_keys($this->definitions))
            ));
        }

        return $this->definitions[$alias];
    }
}
