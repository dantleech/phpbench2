<?php

namespace PhpBench\Library\Result;

interface Result
{
    public function toArray(): array;
    public function name(): string;
}
