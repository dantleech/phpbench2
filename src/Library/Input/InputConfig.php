<?php

namespace PhpBench\Library\Input;

class InputConfig
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $alias, array $params)
    {
        $this->alias = $alias;
        $this->params = $params;
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function params(): array
    {
        return $this->params;
    }
}
