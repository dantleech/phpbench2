<?php

namespace PhpBench\Library\Visualize;

use PhpBench\Library\TypeSpec\Type;

/**
 * @virtual-method string __invoke(array $data, ...$params)
 */
interface Renderer
{
    public function accepts(): Type;
}
