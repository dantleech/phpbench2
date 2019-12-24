<?php

namespace PhpBench\Extension\Sampler\Command;

use DTL\Invoke\Invoke;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use PhpBench\Library\Sampler\SamplerLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class SampleCommand extends Command
{
    const ARG_SAMPLER = 'sampler';
    const ARG_PARAMETERS = 'parameters';


    /**
     * @var SamplerLocator
     */
    private $locator;

    /**
     * @var MethodToDefinitionConverter
     */
    private $converter;

    public function __construct(SamplerLocator $locator, MethodToConsoleOptionsBroker $converter)
    {
        parent::__construct();
        $this->locator = $locator;
        $this->converter = $converter;
    }

    protected function configure()
    {
        $this->addArgument(self::ARG_SAMPLER, InputArgument::REQUIRED, 'Sampler alias');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::IS_ARRAY, 'Sampler parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = (string)$input->getArgument(self::ARG_SAMPLER);
        $sampler = $this->locator->get($alias);

        $input = new StringInput(implode(
            ' ',
            array_map(
                'escapeshellarg',
                $input->getArgument(self::ARG_PARAMETERS)
            )
        ));

        $definition = $this->converter->createInputDefinition(
            get_class($sampler),
            '__invoke'
        );

        $input->bind($definition);

        Invoke::method($sampler, '__invoke', array_filter($input->getOptions()));
    }
}
