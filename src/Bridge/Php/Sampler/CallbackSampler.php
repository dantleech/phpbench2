<?php

namespace PhpBench\Bridge\Php\Sampler;

use PhpBench\Bridge\Php\Result\HrTimeResult;
use PhpBench\Bridge\Php\Sampler\CallbackParams;
use PhpBench\Library\Result\Results;
use PhpBench\Library\Sampler\Sampler;

class CallbackSampler implements Sampler
{
    public function __invoke(
        $callback,
        $iterations,
        $param = []
    ): Results
    {
        $start = hrtime();
        $callback = $callback();

        for ($i = 0; $i < $iterations; $i++) {
            $callback($callback->parameters());
        }

        $end = hrtime();

        return Results::one([
            new HrTimeResult($start, $end),
        ]);
    }
}
