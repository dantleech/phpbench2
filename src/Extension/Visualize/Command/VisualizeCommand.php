<?php

namespace PhpBench\Extension\Visualize\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use PhpBench\Bridge\Console\CliParametersToInvokableParameters;
use PhpBench\Library\Cast\Cast;
use PhpBench\Library\Input\InputConfig;
use PhpBench\Library\Output\OutputConfig;
use PhpBench\Library\Visualize\Renderer;
use PhpBench\Library\Visualize\RendererConfig;
use PhpBench\Library\Visualize\RendererLocator;
use PhpBench\Library\Visualize\Visualizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class VisualizeCommand extends Command
{
    private const ARG_VISUALIZER = 'visualizer';
    private const ARG_PARAMETERS = 'parameters';

    /**
     * @var RendererLocator
     */
    private $locator;

    /**
     * @var Visualizer
     */
    private $visualizer;

    public function __construct(RendererLocator $locator, Visualizer $visualizer)
    {
        parent::__construct();
        $this->locator = $locator;
        $this->visualizer = $visualizer;
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_VISUALIZER, InputArgument::REQUIRED, 'Visualizer alias');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::IS_ARRAY, 'Visualizer parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = Cast::toString($input->getArgument(self::ARG_VISUALIZER));

        $renderer = $this->locator->get($alias);

        $rendererOptions = CliParametersToInvokableParameters::convert(
            $renderer,
            Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
        );

        $inputConfig = new InputConfig('stream', [
            'stream' => 'php://stdin',
        ]);
        $outputConfig = new OutputConfig('stream', [
            'stream' => 'php://stdout',
        ]);
        $rendererConfig = new RendererConfig($alias, $rendererOptions);

        $this->visualizer->visualize(
            $inputConfig,
            $outputConfig,
            $rendererConfig
        );

        return 0;
    }
}
