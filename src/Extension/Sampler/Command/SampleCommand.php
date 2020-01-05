<?php

namespace PhpBench\Extension\Sampler\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\CliParametersToInvokableParameters;
use PhpBench\Library\Cast\Cast;
use PhpBench\Library\Sampler\SamplerLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SampleCommand extends Command
{
    private const ARG_SAMPLER = 'sampler';
    private const ARG_PARAMETERS = 'parameters';
    private const OPT_CHANNEL = 'channel';
    private const OPT_SAMPLES = 'samples';

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
        $this->addOption(self::OPT_CHANNEL, 'l', InputOption::VALUE_REQUIRED, 'Label for samples');
        $this->addOption(self::OPT_SAMPLES, 's', InputOption::VALUE_REQUIRED, 'Number of samples to take', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $label = Cast::toStringOrNull($input->getOption(self::OPT_CHANNEL));
        $label = $label ?: uniqid();
        $alias = Cast::toString($input->getArgument(self::ARG_SAMPLER));
        $sampler = $this->locator->get($alias);

        $options = CliParametersToInvokableParameters::convert(
            $sampler,
            Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
        );

        $stdin = fopen('php://stdin', 'r');
        $stdout = fopen('php://stdout', 'r');
        $write = $except = [];
        $readingFromStdin = false;

        $samples = (int)$input->getOption(self::OPT_SAMPLES);
        if (0 >= $samples) {
            $samples = PHP_INT_MAX;
        }
        for ($i = 0; $i < $samples; $i++) {

            // pass-through any data from prior processes
            $read = [$stdin];
            if (stream_select($read, $write, $except, 0)) {
                $line = fgets($stdin);
                fwrite($stdout, $line);
                $readingFromStdin = true;
            }

            $results = Invoke::method($sampler, '__invoke', array_filter($options));
            fwrite($stdout, json_encode(array_merge([
                'channel' => $label,
            ], $results->toArray()), JSON_THROW_ON_ERROR)."\n");
        }

        // pass-through any remaining data from prior processes
        while (false !== $line = fgets($stdin)) {
            fwrite($stdout, $line);
        }

        fclose($stdin);
        fclose($stdout);

        return 0;
    }
}
