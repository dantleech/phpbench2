<?php

namespace PhpBench\Library\TypeSpec;

abstract class Type
{
    abstract public function accepts($data): bool;

    abstract public function __toString(): string;
}
