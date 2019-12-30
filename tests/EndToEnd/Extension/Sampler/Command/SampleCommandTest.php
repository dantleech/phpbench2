<?php

namespace PhpBench\Tests\EndToEnd\Extension\Sampler\Command;

use PhpBench\Tests\IntegrationTestCase;

class SampleCommandTest extends IntegrationTestCase
{
    public function testTakeSingleSample()
    {
        $process = self::exec('sample constant --label=one -- --value=100');
        self::assertProcessSuccess($process);
        $output = $process->getOutput();
        $decoded = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals([
            'label' => 'one',
            'value' => 100
        ], $decoded);
    }

    public function testTakeManySamples()
    {
        $process = self::exec('sample constant --samples=2 --label=one -- --value=100');
        self::assertProcessSuccess($process);
        $output = $process->getOutput();

        $samples = array_map(function (string $line) {
            return json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        }, array_filter(explode("\n", $output)));

        self::assertEquals([
            [
                'label' => 'one',
                'value' => 100
            ],
            [
                'label' => 'one',
                'value' => 100
            ],
        ], $samples);
    }
}
