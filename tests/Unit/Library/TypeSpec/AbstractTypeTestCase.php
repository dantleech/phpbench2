<?php

namespace PhpBench\Tests\Unit\Library\TypeSpec;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpBench\Library\TypeSpec\Type;

abstract class AbstractTypeTestCase extends TestCase
{
    /**
     * @dataProvider provideAccepts
     */
    public function testAccepts(Type $type, $data, bool $accepts)
    {
        self::assertEquals($accepts, $type->accepts($data));
    }

    abstract public function provideAccepts(): Generator;

    /**
     * @dataProvider provideToString
     */
    public function testToString(Type $type, string $expected)
    {
        self::assertEquals($expected, $type->__toString());
    }

    abstract public function provideToString(): Generator;
}
