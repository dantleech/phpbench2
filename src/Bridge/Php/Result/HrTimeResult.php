<?php

namespace PhpBench\Bridge\Php\Result;

use PhpBench\Library\Result\Result;

final class HrTimeResult implements Result
{
    const NAME = 'hrTime';

    /**
     * @var float
     */
    private $start;

    /**
     * @var float
     */
    private $end;

    /**
     * @var int
     */
    private $iterations;

    public function __construct(float $start, float $end, int $iterations)
    {
        $this->start = $start;
        $this->end = $end;
        $this->iterations = $iterations;
    }

    public function end(): float
    {
        return $this->end;
    }

    public function start(): float
    {
        return $this->start;
    }

    public function value(): array
    {
        $duration = $this->end - $this->start;

        return [
            'start' => $this->start,
            'end' => $this->end,
            'duration' => $duration,
            'iterations' => $this->iterations,
            'mean' => $duration / $this->iterations,
        ];
    }

    public function name(): string
    {
        return self::NAME;
    }
}
