<?php

namespace PhpBench\Library\Visualize;

use PhpBench\Library\Visualize\Visualizer;

interface VisualizerLocator
{
    public function get(string $alias): Visualizer;
}
