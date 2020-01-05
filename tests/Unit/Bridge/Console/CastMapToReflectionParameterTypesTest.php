<?php

namespace PhpBench\Tests\Unit\Bridge\Console;

use PHPUnit\Framework\TestCase;
use PhpBench\Bridge\Console\CastMapToReflectionParameterTypes;
use ReflectionClass;

class CastMapToReflectionParameterTypesTest extends TestCase
{
    public function testCast()
    {
        $reflectionMethod = (new ReflectionClass(TestClass::class))->getMethod('test');
        $options = [
            'unknown' => 'unknown',
            'string' => 'string',
            'int' => '1234',
            'float' => '1.123',
            'array' => [ '1234 '],
            'bool' => 0
        ];

        self::assertSame([
            'unknown' => 'unknown',
            'string' => 'string',
            'int' => 1234,
            'float' => 1.123,
            'array' => [ '1234 '],
            'bool' => false,
        ], (new CastMapToReflectionParameterTypes())->__invoke($reflectionMethod, $options));
    }
}

class TestClass
{
    public function test($unknown, string $string, int $int, array $array, float $float, bool $bool)
    {
    }
}
