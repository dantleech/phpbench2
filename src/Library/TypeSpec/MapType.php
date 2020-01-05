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
        foreach ($data as $key => $value) {
            if (!$this->key->accepts($key) || !$this->value->accepts($value)) {
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
