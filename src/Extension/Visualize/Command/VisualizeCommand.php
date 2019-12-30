<?php

namespace PhpBench\Extension\Visualize\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use PhpBench\Bridge\Console\CliParametersToInvokableParameters;
use PhpBench\Library\Cast\Cast;
use PhpBench\Library\Visualize\Visualizer;
use PhpBench\Library\Visualize\VisualizerLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class VisualizeCommand extends Command
{
    const ARG_VISUALIZER = 'visualizer';
    const ARG_PARAMETERS = 'parameters';

    /**
     * @var VisualizerLocator
     */
    private $locator;

    public function __construct(VisualizerLocator $locator)
    {
        parent::__construct();
        $this->locator = $locator;
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_VISUALIZER, InputArgument::REQUIRED, 'Visualizer alias');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::IS_ARRAY, 'Visualizer parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = Cast::toString($input->getArgument(self::ARG_VISUALIZER));

        $visualizer = $this->locator->get($alias);
        $options = CliParametersToInvokableParameters::convert(
            $visualizer,
            Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
        );

        $values = [];
        $numberOfLinesToClear = -1;
        $stream = fopen('php://stdout', 'w');
        while ($data = fgets(STDIN)) {
            $values = (array)json_decode($data, true);

            fwrite($stream, sprintf("\x1B[%dA", $numberOfLinesToClear));
            fwrite($stream, "\x1B[0J");

            $result = Invoke::method($visualizer, '__invoke', array_merge([
                'values' => $values,
            ], array_filter($options)));

            fwrite(STDOUT, $result);
            $numberOfLinesToClear = substr_count($result, "\n");
        }

        return 0;
    }
}
