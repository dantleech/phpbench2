<?php

namespace PhpBench\Library\DataStructure;

final class DataStructureAssert
{
    public static function isList(array $data)
    {
        $index = 0;
        foreach (array_keys($data) as $key) {
            if ($key !== $index++) {
                return false;
            }
        }

        return true;
    }

    public static function areValuesNumeric(array $data): bool
    {
        foreach ($data as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    public static function areValuesNumericArrays(array $data): bool
    {
        foreach ($data as $row) {
            if (!is_array($row)) {
                return false;
            }

            if (!self::areValuesNumeric($row)) {
                return false;
            }
        }

        return true;
    }

    public static function areValuesArrays(array $data)
    {
        foreach ($data as $row) {
            if (!is_array($row)) {
                return false;
            }
        }

        return true;
    }
}
