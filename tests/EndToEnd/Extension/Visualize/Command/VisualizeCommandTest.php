<?php

namespace PhpBench\Tests\EndToEnd\Extension\Visualize\Command;

use PhpBench\Tests\IntegrationTestCase;

class VisualizeCommandTest extends IntegrationTestCase
{
    public function testVisualizeFromStdin()
    {
        $process = self::exec('visualize test -- --greeting=hello', "[\"foo\",\"bar\"]\n");
        self::assertProcessSuccess($process);
        $output = $process->getOutput();
        self::assertStringContainsString('hello "foo" hello "bar"', $output);
    }
}
