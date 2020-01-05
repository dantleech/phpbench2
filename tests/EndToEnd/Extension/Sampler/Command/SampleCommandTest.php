<?php

namespace PhpBench\Tests\EndToEnd\Extension\Sampler\Command;

use PhpBench\Tests\IntegrationTestCase;

class SampleCommandTest extends IntegrationTestCase
{
    public function testTakeSingleSample()
    {
        $process = self::exec('sample constant --channel=one -- --value=100');
        self::assertProcessSuccess($process);
        $output = $process->getOutput();
        $decoded = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        self::assertEquals([
            'channel' => 'one',
            'value' => 100
        ], $decoded);
    }

    public function testTakeManySamples()
    {
        $process = self::exec('sample constant --samples=2 --channel=one -- --value=100');
        self::assertProcessSuccess($process);
        $output = $process->getOutput();

        $samples = array_map(function (string $line) {
            return json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        }, array_filter(explode("\n", $output)));

        self::assertEquals([
            [
                'channel' => 'one',
                'value' => 100
            ],
            [
                'channel' => 'one',
                'value' => 100
            ],
        ], $samples);
    }

    public function testFailsOnUnknownSampler()
    {
        $process = self::exec('sample notexisting --samples=2 --channel=one -- --value=100');
        self::assertProcessFailure($process, 'Sampler with alias "notexisting" not found');
    }
}
