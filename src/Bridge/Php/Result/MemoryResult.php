<?php

namespace PhpBench\Bridge\Php\Result;

use PhpBench\Library\Result\Result;

class MemoryResult implements Result
{
    /**
     * @var int
     */
    private $peakUsage;

    public function __construct(int $peakUsage)
    {
        $this->peakUsage = $peakUsage;
    }

    public function peakUsage(): int
    {
        return $this->peakUsage;
    }

    public function value(): array
    {
        return [
            'peak' => $this->peakUsage,
        ];
    }

    public function name(): string
    {
        return 'phpmemory';
    }
}
