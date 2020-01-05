<?php

namespace PhpBench\Library\Visualize;

interface RendererLocator
{
    public function get(string $alias): Renderer;

    public function forData(array $data): Renderer;
}
