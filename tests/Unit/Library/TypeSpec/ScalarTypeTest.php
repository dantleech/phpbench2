<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use Generator;
use PhpBench\Library\TypeSpec\TypeFactory;

class ScalarTypeTest extends AbstractTypeTestCase
{
    public function provideAccepts(): Generator
    {
        yield 'accept number' => [
            TypeFactory::scalar(),
            12,
            true
        ];
        yield 'accept string' => [
            TypeFactory::scalar(),
            12,
            true
        ];
        yield 'not accept array' => [
            TypeFactory::scalar(),
            [12],
            false
        ];
    }

    public function provideToString(): Generator
    {
        yield [
            TypeFactory::scalar(),
            'scalar'
        ];
    }
}
