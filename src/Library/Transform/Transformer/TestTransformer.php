<?php

namespace PhpBench\Library\Transform\Transformer;

use PhpBench\Library\Transform\Transformer;

class TestTransformer implements Transformer
{
    public function __invoke(array $data, int $append): array
    {
        return array_merge($data, [$append]);
    }
}
