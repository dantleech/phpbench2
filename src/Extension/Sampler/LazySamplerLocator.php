<?php

namespace PhpBench\Extension\Sampler;

use PhpBench\Library\Sampler\Exception\SamplerNotFound;
use PhpBench\Library\Sampler\Sampler;
use PhpBench\Library\Sampler\SamplerLocator;
use Psr\Container\ContainerInterface;

class LazySamplerLocator implements SamplerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var SamplerDefinition[]
     */
    private $definitions = [];

    public function __construct(ContainerInterface $container, SamplerDefinition ...$definitions)
    {
        $this->container = $container;
        array_map(function (SamplerDefinition $definition) {
            $this->definitions[$definition->alias()] = $definition;
        }, $definitions);
    }

    public function get(string $alias): Sampler
    {
        return $this->container->get($this->getDefinition($alias)->serviceId());
    }

    private function getDefinition(string $alias): SamplerDefinition
    {
        if (!isset($this->definitions[$alias])) {
            throw new SamplerNotFound(sprintf(
                'Sampler with alias "%s" not found, known sampler alises: "%s"',
                $alias, implode('", "', array_keys($this->definitions))
            ));
        }

        return $this->definitions[$alias];
    }
}
