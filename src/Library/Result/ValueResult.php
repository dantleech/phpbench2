<?php

namespace PhpBench\Library\Result;

class ValueResult implements Result
{
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function value()
    {
        return $this->value;
    }

    public function name(): string
    {
        return 'value';
    }
}
