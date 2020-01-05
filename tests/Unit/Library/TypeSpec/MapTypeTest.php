<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use Generator;
use PhpBench\Library\TypeSpec\TypeFactory;

class MapTypeTest extends AbstractTypeTestCase
{
    public function provideAccepts(): Generator
    {
        yield 'valid string to string map' => [
            TypeFactory::map(TypeFactory::string(), TypeFactory::string()),
            ['asd' => 'asd'],
            true
        ];

        yield 'invalid string to string map' => [
            TypeFactory::map(TypeFactory::string(), TypeFactory::string()),
            [1 => 'asd'],
            false
        ];

        yield 'not accept mixed array' => [
            TypeFactory::map(TypeFactory::string(), TypeFactory::number()),
            [
                'foobar' => 'one',
                'barfoo' => [
                    'hrtime' => 1234,
                ],
                'number' => 123.212
            ],
            false
        ];
    }

    public function provideToString(): Generator
    {
        yield 'map of numbers' => [
            TypeFactory::map(TypeFactory::string(), TypeFactory::number()),
            'map<string,number>',
        ];
    }
}
