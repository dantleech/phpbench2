<?php

namespace PhpBench\Library\Sampler\Sampler;

use PhpBench\Library\Result\Results;
use PhpBench\Library\Result\ValueResult;
use PhpBench\Library\Sampler\Sampler;

class ConstantSampler implements Sampler
{
    public function __invoke(int $value)
    {
        return Results::one(new ValueResult($value));
    }
}
