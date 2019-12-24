<?php

namespace PhpBench;

use PhpBench\Extension\Sampler\SamplerExtension;
use Phpactor\Container\PhpactorContainer;
use Phpactor\Extension\Console\ConsoleExtension;
use Symfony\Component\Console\Application;

class ApplicationBuilder
{
    public function build(): Application
    {
        $application = new Application('PhpBench');
        $container = PhpactorContainer::fromExtensions([
            ConsoleExtension::class,
            SamplerExtension::class,
        ]);
        $application->setCommandLoader(
            $container->get(ConsoleExtension::SERVICE_COMMAND_LOADER)
        );

        return $application;
    }
}
