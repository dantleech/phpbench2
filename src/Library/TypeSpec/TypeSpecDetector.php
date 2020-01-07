<?php

namespace PhpBench\Library\TypeSpec;

use Generator;
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

        if (null === $data) {
            return new MixedType();
        }

        foreach ($data as $key => $value) {
            foreach (self::arrayLikeTypes($key, $value) as $type) {
                if (!$type->accepts($data)) {
                    continue;
                }
                return $type;
            }
        }

        return new MixedType();
    }

    private static function arrayLikeTypes($key, $value): Generator
    {
        yield new ListType(
            self::resolveType($value)
        );

        yield new ListType(
            new MixedType(),
        );

        yield new MapType(
            self::resolveType($key),
            self::resolveType($value)
        );

        yield new MapType(
            self::resolveType($key),
            new MixedType(),
        );

        yield new MapType(
            new ScalarType(),
            new MixedType(),
        );
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

    private static function scalarTypes(): array
    {
        return [
            new NumberType(),
            new StringType(),
            new ScalarType(),
        ];
    }
}
