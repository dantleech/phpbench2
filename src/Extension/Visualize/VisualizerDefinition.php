<?php

namespace PhpBench\Extension\Visualize;

use DTL\Invoke\Invoke;

final class VisualizerDefinition
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $serviceId;

    public function __construct(string $alias, string $serviceId)
    {
        $this->alias = $alias;
        $this->serviceId = $serviceId;
    }

    /**
     * @param array<string,mixed> $definition
     */
    public static function fromArray(array $definition): self
    {
        return Invoke::new(self::class, $definition);
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function serviceId(): string
    {
        return $this->serviceId;
    }
}
