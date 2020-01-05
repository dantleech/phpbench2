<?php

namespace PhpBench\Bridge\Extension\Exception;

use RuntimeException;

class AliasedServiceNotFound extends RuntimeException
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $knownAliases;

    public function __construct(string $alias, array $knownAliases)
    {
        $this->alias = $alias;
        $this->knownAliases = $knownAliases;
        parent::__construct(sprintf(
            'Service with alias "%s" not found',
            $alias
        ));
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function knownAliases(): array
    {
        return $this->knownAliases;
    }
}
