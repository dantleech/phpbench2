<?php

namespace PhpBench\Library\Visualize;

class RendererConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $name, array $params)
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function params(): array
    {
        return $this->params;
    }
}
