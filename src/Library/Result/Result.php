<?php

namespace PhpBench\Library\Result;

interface Result
{
    /**
     * @return string|float|int|bool|array<mixed>
     */
    public function value();

    public function name(): string;
}
