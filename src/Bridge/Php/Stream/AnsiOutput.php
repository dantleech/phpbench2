<?php

namespace PhpBench\Bridge\Php\Stream;

use PhpBench\Library\Output\Output;
use PhpBench\Library\Stream\Stream;

class AnsiOutput implements Output
{
    /**
     * @var Output
     */
    private $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    public function __invoke(
        string $uri = 'php://stdout',
        bool $clear = true
    ): Stream
    {
        return new AnsiStreamDecorator(
            $this->output->__invoke($uri, 'w'),
            $clear
        );
    }
}
