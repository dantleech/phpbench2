<?php

namespace PhpBench\Bridge\MathPhp\Transform;

use MathPHP\Statistics\KernelDensityEstimation;

final class KernelDensityTransformer
{
    /**
     * @param array<float> $data
     * @return array<float>
     */
    public function __invoke(
        array $data,
        int $points,
        float $bandwidth = 0.1,
        string $function = KernelDensityEstimation::NORMAL
    ): array
    {
        $values = [];
        $estimate = new KernelDensityEstimation($data, $bandwidth, $function);
        $step = 1 / $points;
        for ($x = 0; $x < 1; $x += $step) {
            $values[] = $estimate->evaluate($x);
        }

        return $values;
    }
}
