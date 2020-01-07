<?php

namespace PhpBench\Library\TypeSpec;

class MapType extends Type
{
    /**
     * @var Type
     */
    private $key;

    /**
     * @var Type
     */
    private $value;

    public function __construct(Type $key, Type $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function accepts($data): bool
    {
        if (!is_array($data)) {
            return false;
        }

        if (range(0, count($data) - 1) === array_keys($data)) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (
                !$this->key->accepts($key) || !$this->value->accepts($value)
            ) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return 'map<' . $this->key->__toString() . ','.$this->value->__toString().'>';
    }
}
