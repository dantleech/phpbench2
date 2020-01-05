<?php

namespace PhpBench\Library\Stream;

interface Stream
{
    public function readData(): array;

    public function close(): void;

    public function write(string $data): void;
}
