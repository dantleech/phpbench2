<?php

namespace PhpBench\Library\Json;

class JsonDecode
{
    public static function decode(string $json): array
    {
        return (array)json_decode($json);
    }
}
