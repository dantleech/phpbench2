<?php

namespace PhpBench\Library\Visualize\Renderer;

use PhpBench\Library\DataStructure\MixedList;
use PhpBench\Library\TypeSpec\Type;
use PhpBench\Library\TypeSpec\TypeFactory;
use PhpBench\Library\Visualize\Renderer;

class TestRenderer implements Renderer
{
    public function __invoke(array $data, string $greeting): string
    {
        return implode(" ", array_map(function (string $value) use ($greeting) {
            return $greeting . ' ' . $value;
        }, $data->toArray()));
    }

    public function accepts(): Type
    {
        return TypeFactory::list(
            TypeFactory::string()
        );
    }
}
