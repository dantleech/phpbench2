<?php

namespace PhpBench\Library\TypeSpec;

class UnknownType extends Type
{
    public function accepts($data): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'unknown';
    }
}
