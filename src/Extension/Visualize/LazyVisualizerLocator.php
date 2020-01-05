<?php

namespace PhpBench\Extension\Visualize;

use PhpBench\Library\Visualize\Exception\RendererNotFound;
use PhpBench\Library\Visualize\Renderer;
use PhpBench\Library\Visualize\RendererLocator;
use Psr\Container\ContainerInterface;
use RuntimeException;

class LazyVisualizerLocator implements RendererLocator
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

    public function get(string $alias): Renderer
    {
        return $this->container->get($this->getDefinition($alias)->serviceId());
    }

    public function forData(array $data): Renderer
    {
        $accepts = [];
        $acceptedTypes = [];
        foreach ($this->definitions as $definition) {
            $renderer = $this->get($definition->alias());
            if ($renderer->accepts()->accepts($data)) {
                return $renderer;
            }
            $acceptedTypes[] = sprintf('%s: %s', $definition->alias(), $renderer->accepts()->__toString());
        }

        throw new RuntimeException(sprintf(
            'Could not find renderer for given data structure. Can handle the following: %s%s',
            "\n  - ",
            implode("\n  - ", $acceptedTypes)
        ));
    }

    private function getDefinition(string $alias): VisualizerDefinition
    {
        if (!isset($this->definitions[$alias])) {
            throw new RendererNotFound(sprintf(
                'Visualizer with alias "%s" not found, known visualizer alises: "%s"',
                $alias,
                implode('", "', array_keys($this->definitions))
            ));
        }

        return $this->definitions[$alias];
    }
}
