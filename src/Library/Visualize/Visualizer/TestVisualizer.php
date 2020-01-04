<?php

namespace PhpBench\Library\Visualize\Visualizer;

use PhpBench\Library\Visualize\Visualizer;

class TestVisualizer implements Visualizer
{
    public function __invoke(array $values, string $greeting): string
    {
        return implode(" ", array_map(function ($value) use ($greeting) {
            return $greeting . ' ' . json_encode($value);
        }, $values));
    }
}
