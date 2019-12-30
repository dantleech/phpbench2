<?php

namespace PhpBench\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class IntegrationTestCase extends TestCase
{
    public static function exec(string $command): Process
    {
        $process = Process::fromShellCommandline(__DIR__ . '/../bin/phpbench ' . $command);
        $process->run();
        return $process;
    }

    public static function assertProcessSuccess(Process $process): void
    {
        if ($process->getExitCode() === 0) {
            return;
        }

        self::fail(sprintf(
            'Failed asserting process success, it returned "%s": ERR: %s',
            $process->getExitCode(),
            $process->getErrorOutput()
        ));
    }

    public static function assertProcessFailure(Process $process): void
    {
        if ($process->getExitCode() !== 0) {
            return;
        }

        self::fail(sprintf(
            'Failed asserting process failed, it returned "%s"',
            $process->getExitCode()
        ));
    }
}
