<?php

namespace PhpBench\Library\Input;

interface Stream
{
    public function readData(): array;

    public function close(): void;
}
