<?php

namespace PhpBench\Extension\Sampler;

use DTL\Invoke\Invoke;

final class SamplerDefinition
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
