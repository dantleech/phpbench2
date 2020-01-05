<?php

namespace PhpBench\Library\TypeSpec;

class ScalarType extends Type
{
    public function accepts($data): bool
    {
        return is_scalar($data);
    }

    public function __toString(): string
    {
        return 'scalar';
    }
}
