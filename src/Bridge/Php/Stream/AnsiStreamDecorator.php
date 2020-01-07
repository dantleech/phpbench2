<?php

namespace PhpBench\Bridge\Php\Stream;

use PhpBench\Library\Stream\Stream;

class AnsiStreamDecorator implements Stream
{
    /**
     * @var Stream
     */
    private $stream;

    /**
     * @var int
     */
    private $numberOfLinesToClear = -1;

    /**
     * @var bool
     */
    private $clear;

    public function __construct(Stream $stream, bool $clear = true)
    {
        $this->stream = $stream;
        $this->clear = $clear;
    }

    public function readData(): array
    {
        return $this->stream->readData();
    }

    public function close(): void
    {
        $this->stream->close();
    }

    public function write(string $data): void
    {
        if ($this->clear) {
            $this->stream->write(sprintf("\x1B[%dA", $this->numberOfLinesToClear));
            $this->stream->write("\x1B[0J");
        }

        $this->stream->write($data);
        $this->numberOfLinesToClear = substr_count($data, "\n");
    }
}
