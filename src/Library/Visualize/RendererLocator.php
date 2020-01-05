<?php

namespace PhpBench\Library\Visualize;

use PhpBench\Library\Visualize\Renderer;

interface RendererLocator
{
    public function get(string $alias): Renderer;
}
