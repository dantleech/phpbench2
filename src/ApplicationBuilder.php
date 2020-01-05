<?php

namespace PhpBench;

use PhpBench\Extension\DataStructure\DataStructureExtension;
use PhpBench\Extension\Input\InputExtension;
use PhpBench\Extension\Output\OutputExtension;
use PhpBench\Extension\Sampler\SamplerExtension;
use PhpBench\Extension\Transform\TransformExtension;
use PhpBench\Extension\Visualize\VisualizerExtension;
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
            TransformExtension::class,
            VisualizerExtension::class,
            InputExtension::class,
            OutputExtension::class,
        ]);
        $application->setCommandLoader(
            $container->get(ConsoleExtension::SERVICE_COMMAND_LOADER)
        );

        return $application;
    }
}
