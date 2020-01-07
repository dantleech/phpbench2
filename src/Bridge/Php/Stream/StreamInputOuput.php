<?php

namespace PhpBench\Bridge\Php\Stream;

use PhpBench\Library\Input\Input;
use PhpBench\Library\Output\Output;
use PhpBench\Library\Stream\Stream;

class StreamInputOuput implements Input, Output
{
    public function __invoke(string $uri, string $mode = 'rw'): Stream
    {
        return new ResourceStream(fopen($uri, $mode));
    }
}
