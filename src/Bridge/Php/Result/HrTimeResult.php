<?php

namespace PhpBench\Bridge\Php\Result;

use PhpBench\Library\Result\Result;

final class HrTimeResult implements Result
{
    /**
     * @var float
     */
    private $start;

    /**
     * @var float
     */
    private $end;

    public function __construct(float $start, float $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function end(): float
    {
        return $this->end;
    }

    public function start(): float
    {
        return $this->start;
    }
}
