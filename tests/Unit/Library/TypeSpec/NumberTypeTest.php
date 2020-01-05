<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use Generator;
use PhpBench\Library\TypeSpec\TypeFactory;

class NumberTypeTest extends AbstractTypeTestCase
{
    public function provideAccepts(): Generator
    {
        yield 'accept number' => [
            TypeFactory::number(),
            12,
            true
        ];
        yield 'not accept number for string' => [
            TypeFactory::number(),
            'asd',
            false
        ];
    }

    public function provideToString(): Generator
    {
        yield [
            TypeFactory::number(),
            'number'
        ];
    }
}
