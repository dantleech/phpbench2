<?php

namespace PhpBench\Library\TypeSpec;

final class TypeFactory
{
    public static function list(Type $type): ListType
    {
        return new ListType($type);
    }

    public static function number(): NumberType
    {
        return new NumberType();
    }

    public static function map(Type $key, Type $value)
    {
        return new MapType($key, $value);
    }

    public static function string()
    {
        return new StringType();
    }
}
