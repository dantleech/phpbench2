<?php

namespace PhpBench\Library\DataStructure;

class Nothing implements Data
{
    public function toArray(): array
    {
        return [];
    }
}
