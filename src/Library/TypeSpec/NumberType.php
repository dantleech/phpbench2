<?php

namespace PhpBench\Library\TypeSpec;

class NumberType extends Type
{
    public function accepts($data): bool
    {
        return is_numeric($data);
    }

    public function __toString(): string
    {
        return 'number';
    }
}
