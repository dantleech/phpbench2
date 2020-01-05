<?php

namespace PhpBench\Library\Json;

use RuntimeException;

class JsonDecode
{
    public static function decode(string $json): array
    {
        if (empty($json)) {
            return [];
        }
        if (null === $decoded = json_decode($json, true)) {
            throw new RuntimeException(sprintf(
                'Could not decode JSON: %s',
                json_last_error_msg()
            ));
        }

        return (array)$decoded;
    }
}
