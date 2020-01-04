<?php

namespace PhpBench\Extension\Transform\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\CliParametersToInvokableParameters;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use PhpBench\Library\Cast\Cast;
use PhpBench\Library\Transform\Transformer;
use PhpBench\Library\Transform\TransformerLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class TransformCommand extends Command
{
    private const ARG_TRANSFORMER = 'transformer';
    private const ARG_PARAMETERS = 'parameters';

    /**
     * @var TransformerLocator
     */
    private $locator;

    public function __construct(TransformerLocator $locator)
    {
        parent::__construct();
        $this->locator = $locator;
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_TRANSFORMER, InputArgument::REQUIRED, 'Transformer alias');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::IS_ARRAY, 'Transformer parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = Cast::toString($input->getArgument(self::ARG_TRANSFORMER));
        $transformer = $this->locator->get($alias);

        $options = CliParametersToInvokableParameters::convert(
            $transformer,
            Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
        );

        $values = [];
        while ($data = fgets(STDIN)) {
            $values[] = json_decode($data, true);

            $result = Invoke::method($transformer, '__invoke', array_merge([
                'data' => $values,
            ], array_filter($options)));

            $output->write(json_encode($result, JSON_THROW_ON_ERROR), true, OutputInterface::OUTPUT_RAW);
        }

        return 0;
    }
}
