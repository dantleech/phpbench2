<?php

namespace PhpBench\Extension\Transform\Command;

use DTL\Invoke\Invoke;
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
    const ARG_TRANSFORMER = 'transformer';
    const ARG_PARAMETERS = 'parameters';

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

        $options = $this->resolveTransformerOptions($input, $transformer);

        $values = [];
        while ($data = fgets(STDIN)) {
            $values[] = json_decode($data, true);

            if (count($values) < 2) {
                continue;
            }

            $result = Invoke::method($transformer, '__invoke', array_merge([
                'data' => $values,
            ], array_filter($options)));

            $output->write(json_encode($result, JSON_THROW_ON_ERROR), true, OutputInterface::OUTPUT_RAW);
        }

        return 0;
    }

    /**
     * @return array<string,mixed>
     */
    private function resolveTransformerOptions(InputInterface $input, Transformer $transformer): array
    {
        $input = new StringInput(implode(
            ' ',
            array_map(
                'escapeshellarg',
                Cast::toArray($input->getArgument(self::ARG_PARAMETERS))
            )
        ));

        $converter = new MethodToConsoleOptionsBroker(get_class($transformer), '__invoke');

        $input->bind($converter->inputDefinition());
        $options = $converter->castOptions($input->getOptions());
        return $options;
    }
}
