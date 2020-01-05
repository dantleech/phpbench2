<?php

namespace PhpBench\Bridge\Extension;

use PhpBench\Bridge\Extension\Exception\AliasedServiceNotFound;
use Psr\Container\ContainerInterface;

abstract class AliasedServiceLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TransformerDefinition[]
     */
    private $definitions = [];

    public function __construct(ContainerInterface $container, AliasedService ...$definitions)
    {
        $this->container = $container;
        array_map(function (AliasedService $definition) {
            $this->definitions[$definition->alias()] = $definition;
        }, $definitions);
    }

    public function getService(string $alias)
    {
        return $this->container->get($this->getDefinition($alias)->serviceId());
    }

    private function getDefinition(string $alias): AliasedService
    {
        if (!isset($this->definitions[$alias])) {
            throw new AliasedServiceNotFound($alias, array_keys($this->definitions));
        }

        return $this->definitions[$alias];
    }
}
