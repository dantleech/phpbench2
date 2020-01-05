<?php

namespace PhpBench\Library\Visualize\Renderer;

use PhpBench\Library\DataStructure\MixedList;
use PhpBench\Library\Visualize\Renderer;

class TestRenderer implements Renderer
{
    public function __invoke(MixedList $data, string $greeting): string
    {
        return implode(" ", array_map(function ($value) use ($greeting) {
            return $greeting . ' ' . json_encode($value);
        }, $data->toArray()));
    }
}
