<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use PHPUnit\Framework\TestCase;
use PhpBench\Library\TypeSpec\TypeSpecDetector;

class TypeSpecDetectorTest extends TestCase
{
    /**
     * @dataProvider provideStringRepresentation
     */
    public function testStringRepresentation($data, string $expectedRepresentation)
    {
        self::assertEquals($expectedRepresentation, TypeSpecDetector::represent($data));
    }

    public function provideStringRepresentation()
    {
        yield 'number' => [
            '1',
            'number',
        ];

        yield 'number list' => [
            ['1', '2'],
            'list<number>',
        ];

        yield 'list of number list' => [
            [['1', '2'],[1,2]],
            'list<list<number>>',
        ];

        yield 'map' => [
            ['one' => 'two'],
            'map<string,string>',
        ];

        yield 'map of lists' => [
            ['one' => [1,2]],
            'map<string,list<number>>',
        ];

    }
}
