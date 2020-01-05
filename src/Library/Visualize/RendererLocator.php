<?php

namespace PhpBench\Library\Visualize;

interface RendererLocator
{
    public function get(string $alias): Renderer;
}
