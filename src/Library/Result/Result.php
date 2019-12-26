<?php

namespace PhpBench\Library\Result;

interface Result
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;

    public function name(): string;
}
