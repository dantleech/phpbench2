<?php

namespace PhpBench\Extension\Transform;

use PhpBench\Library\Transform\Transformer;
use PhpBench\Library\Transform\TransformerLocator;
use PhpBench\Library\Transform\Exception\TransformerNotFound;
use Psr\Container\ContainerInterface;

class LazyTransformLocator implements TransformerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TransformerDefinition[]
     */
    private $definitions = [];

    public function __construct(ContainerInterface $container, TransformerDefinition ...$definitions)
    {
        $this->container = $container;
        array_map(function (TransformerDefinition $definition) {
            $this->definitions[$definition->alias()] = $definition;
        }, $definitions);
    }

    public function get(string $alias): Transformer
    {
        return $this->container->get($this->getDefinition($alias)->serviceId());
    }

    private function getDefinition(string $alias): TransformerDefinition
    {
        if (!isset($this->definitions[$alias])) {
            throw new TransformerNotFound(sprintf(
                'Transformer with alias "%s" not found, known transformer alises: "%s"',
                $alias, implode('", "', array_keys($this->definitions))
            ));
        }

        return $this->definitions[$alias];
    }
}
