<?php

namespace PhpBench\Bridge\Php\Sampler;

use PhpBench\Bridge\Php\Result\HrTimeResult;
use PhpBench\Bridge\Php\Result\MemoryResult;
use PhpBench\Bridge\Php\Sampler\CallbackParams;
use PhpBench\Library\Result\Results;
use PhpBench\Library\Sampler\Sampler;

class CallbackSampler implements Sampler
{
    /**
     * @param callable $callback
     * @param array<string,mixed> $param 
     */
    public function __invoke(
        $callback,
        int $iterations = 1,
        array $param = []
    ): Results
    {
        $start = hrtime(true);

        for ($i = 0; $i < $iterations; $i++) {
            $callback(...$param);
        }

        $end = hrtime(true);

        return Results::many(
            new HrTimeResult($start, $end, $iterations),
            new MemoryResult(memory_get_peak_usage()),
        );
    }
}
