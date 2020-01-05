<?php

namespace PhpBench\Bridge\MathPhp\Transform;

use MathPHP\Statistics\Descriptive;
use PhpBench\Library\Transform\Transformer;

final class DescribeTransformer implements Transformer
{
    /**
     * @param array<float> $data
     * @return array<string,float>
     */
    public function __invoke(
        array $data
    ): array {
        return Descriptive::fiveNumberSummary($data);
    }
}
