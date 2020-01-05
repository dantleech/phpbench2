<?php

namespace PhpBench\Library\DataStructure;

class AbstractArray implements Data
{
    /**
     * @var array
     */
    private $list;

    public function __construct(array $list)
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        return $this->list;
    }
}
