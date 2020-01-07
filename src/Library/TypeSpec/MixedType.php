<?php

namespace PhpBench\Library\TypeSpec;

class MixedType extends Type
{
    public function accepts($data): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'mixed';
    }
}
