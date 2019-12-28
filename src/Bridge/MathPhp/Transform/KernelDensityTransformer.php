<?php

namespace PhpBench\Bridge\MathPhp\Transform;

use MathPHP\Statistics\KernelDensityEstimation;
use PhpBench\Library\Transform\Transformer;

final class KernelDensityTransformer implements Transformer
{
    /**
     * @param array<float> $data
     * @return array<float>
     */
    public function __invoke(
        array $data,
        int $points = 10,
        float $bandwidth = 0.1,
        string $function = KernelDensityEstimation::NORMAL
    ): array
    {
        $values = [];
        $estimate = new KernelDensityEstimation($data, $bandwidth, $function);
        $step = (max($data) - min($data)) / $points;
        for ($x = min($data); $x < max($data); $x += $step) {
            $values[] = $estimate->evaluate($x);
        }

        return $values;
    }
}
