<?php

namespace PhpBench\Library\TypeSpec;

class StringType extends Type
{
    public function accepts($data): bool
    {
        return is_string($data);
    }

    public function __toString(): string
    {
        return 'string';
    }
}
