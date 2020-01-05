<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use Generator;
use PhpBench\Library\TypeSpec\TypeFactory;

class ListTypeTest extends AbstractTypeTestCase
{
    public function provideAccepts(): Generator
    {
        yield 'scalar' => [
            TypeFactory::list(TypeFactory::number()),
            2,
            false
        ];

        yield 'array of numbers' => [
            TypeFactory::list(TypeFactory::number()),
            [12],
            true
        ];

        yield 'not accept number for with strings' => [
            TypeFactory::list(TypeFactory::number()),
            ['asd'],
            false
        ];

        yield 'not accept number for with assoc array' => [
            TypeFactory::list(TypeFactory::number()),
            ['asd' => 12],
            false
        ];
    }

    public function provideToString(): Generator
    {
        yield 'list of numbers' => [
            TypeFactory::list(TypeFactory::number()),
            'list<number>',
        ];
    }
}
