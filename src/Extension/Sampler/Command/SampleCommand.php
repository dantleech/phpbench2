<?php

namespace PhpBench\Extension\Sampler\Command;

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

class SampleCommand extends Command
{
    const ARG_SAMPLER = 'sampler';
    const ARG_PARAMETERS = 'parameters';
    const OPT_LABEL = 'label';
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
        $this->addOption(self::OPT_LABEL, 'l', InputOption::VALUE_REQUIRED, 'Label for samples');
        $this->addOption(self::OPT_SAMPLES, 's', InputOption::VALUE_REQUIRED, 'Number of samples to take', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $label = Cast::toStringOrNull($input->getOption(self::OPT_LABEL));
        $label = $label ?: uniqid();
        $alias = Cast::toString($input->getArgument(self::ARG_SAMPLER));
        $sampler = $this->locator->get($alias);

        $options = $this->resolveSamplerOptions($input, $sampler);
        $stdin = fopen('php://stdin', 'r');
        $stdout = fopen('php://stdout', 'r');
        $write = $except = [];

        for ($i = 0; $i < $input->getOption(self::OPT_SAMPLES); $i++) {

            // pass-through any data from prior processes
            $read = [$stdin];
            if (stream_select($read, $write, $except, 0)) {
                $line = fgets($stdin);
                fwrite($stdout, $line);
            }

            $results = Invoke::method($sampler, '__invoke', array_filter($options));
            fwrite($stdout, json_encode(array_merge([
                'label' => $label,
            ], $results->toArray()), JSON_THROW_ON_ERROR)."\n");
        }

        // pass-through any remaining data from prior processes
        $read = [$stdin];
        if (stream_select($read, $write, $except, 1)) {
            while ($line = fgets($stdin)) {
                fwrite($stdout, $line);
            }
        }

        fclose($stdin);
        fclose($stdout);

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
