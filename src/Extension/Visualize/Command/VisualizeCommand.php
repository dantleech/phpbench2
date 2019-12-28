<?php

namespace PhpBench\Extension\Visualize\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use PhpBench\Library\Cast\Cast;
use PhpBench\Library\Sampler\Sampler;
use PhpBench\Library\Sampler\SamplerLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class VisualizeCommand extends Command
{
    const ARG_SAMPLER = 'sampler';
    const ARG_PARAMETERS = 'parameters';
    const OPT_SAMPLES = 'samples';

    /**
     * @var SamplerLocator
     */
    private $locator;

    public function __construct(SamplerLocator $locator)
    {
        parent::__construct();
        $this->locator = $locator;
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_SAMPLER, InputArgument::REQUIRED, 'Sampler alias');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::IS_ARRAY, 'Sampler parameters');
        $this->addOption(self::OPT_SAMPLES, 's', InputOption::VALUE_REQUIRED, 'Number of samples to take', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = Cast::toString($input->getArgument(self::ARG_SAMPLER));
        $sampler = $this->locator->get($alias);

        $options = $this->resolveSamplerOptions($input, $sampler);

        for ($i = 0; $i < $input->getOption(self::OPT_SAMPLES); $i++) {
            $results = Invoke::method($sampler, '__invoke', array_filter($options));
            $output->write(json_encode($results->toArray(), JSON_THROW_ON_ERROR), true, OutputInterface::OUTPUT_RAW);
        }

        return 0;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveSamplerOptions(InputInterface $input, Sampler $sampler): array
    {
        $input = new StringInput(implode(
            ' ',
            array_map(
                'escapeshellarg',
                Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
            )
        ));

        $converter = new MethodToConsoleOptionsBroker(get_class($sampler), '__invoke');

        $input->bind($converter->inputDefinition());
        $options = $converter->castOptions($input->getOptions());
        return $options;
    }
}
