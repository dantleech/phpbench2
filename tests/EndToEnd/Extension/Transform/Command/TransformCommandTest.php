<?php

namespace PhpBench\Tests\EndToEnd\Extension\Transform\Command;

use PhpBench\Tests\IntegrationTestCase;

class TransformCommandTest extends IntegrationTestCase
{
    public function testTransformFromStdin()
    {
        $process = self::exec('transform test -- --append=2', "1\n");
        self::assertProcessSuccess($process);
        $output = $process->getOutput();
        $decoded = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals([
            1,
            2,
        ], $decoded);
    }
}
