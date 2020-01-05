<?php

namespace PhpBench\Tests\Unit\Bridge\Console;

use PHPUnit\Framework\TestCase;
use PhpBench\Bridge\Console\CliParametersToInvokableParameters;

class CliParametersToInvokableParametersTest extends TestCase
{
    /**
     * @dataProvider provideConvertsRawParameters
     */
    public function testConvertsRawParameters(array $cliParams, array $exected)
    {
        $invokable = new class {
            public function __invoke(string $foo, array $bar)
            {
            }
        };

        self::assertEquals(
            $exected,
            CliParametersToInvokableParameters::convert($invokable, $cliParams)
        );
    }

    public function provideConvertsRawParameters()
    {
        yield [
            [
                '--foo="hello goodbye"',
                '--bar="goodbye"',
            ],
            [
                'foo' => 'hello goodbye',
                'bar' => ['goodbye'],
            ]
        ];

        yield [
            [
                '--foo="hello--goodbye"',
                '--bar="goodbye"',
            ],
            [
                'foo' => 'hello--goodbye',
                'bar' => ['goodbye'],
            ]
        ];
    }
}
