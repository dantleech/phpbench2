<?php

namespace PhpBench\Library\TypeSpec;

use RuntimeException;

class TypeSpecDetector
{
    public static function represent($data): string
    {
        $type = static::resolveType($data);
        return $type->__toString();
    }

    private static function resolveType($data): Type
    {
        if (is_scalar($data)) {
            return self::resolveScalarType($data);
        }

        foreach ($data as $key => $value) {
            $type = new ListType(
                self::resolveType($value)
            );

            if ($type->accepts($data)) {
                return $type;
            }

            $type = new MapType(
                self::resolveType($key),
                self::resolveType($value)
            );

            if ($type->accepts($data)) {
                return $type;
            }
        }

        return new Unknown();
    }

    private static function scalarTypes(): array
    {
        return [
            new NumberType(),
            new StringType(),
            new ScalarType(),
        ];
    }

    private static function resolveScalarType($data)
    {
        foreach (self::scalarTypes() as $scalarType) {
            if ($scalarType->accepts($data)) {
                return $scalarType;
            }
        }

        throw new RuntimeException(sprintf(
            'Could not resolve a scalar type'
        ));
    }
}
