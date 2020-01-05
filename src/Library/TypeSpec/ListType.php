<?php

namespace PhpBench\Library\TypeSpec;

class ListType extends Type
{
    /**
     * @var Type
     */
    private $elementType;

    public function __construct(Type $elementType)
    {
        $this->elementType = $elementType;
    }

    public function accepts($data): bool
    {
        if (!is_array($data)) {
            return false;
        }

        $index = 0;
        foreach ($data as $key => $value) {
            if (false === $this->elementType->accepts($value)) {
                return false;
            }

            if ($key !== $index++) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return 'list<' . $this->elementType->__toString() . '>';
    }
}
