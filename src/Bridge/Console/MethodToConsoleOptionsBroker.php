<?php

namespace PhpBench\Bridge\Console;

use ReflectionClass;
use ReflectionParameter;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

final class MethodToConsoleOptionsBroker
{
    /**
     * @var ReflectionMethod
     */
    private $method;

    public function __construct(string $className, string $methodName)
    {
        $reflection = new ReflectionClass($className);

        if (!$reflection->hasMethod($methodName)) {
            return;
        }

        $this->method = $reflection->getMethod($methodName);
    }

    public function inputDefinition(): InputDefinition {
        
        $definition = new InputDefinition();

        if (!$this->method) {
            return $definition;
        }

        foreach ($this->method->getParameters() as $parameter) {
            $definition->addOption($this->createOption($parameter));
        }

        return $definition;
    }

    private function createArgument(ReflectionParameter $parameter)
    {
        return new InputArgument($parameter->getName(), InputArgument::REQUIRED);
    }

    private function createOption(ReflectionParameter $parameter)
    {
        $option = new InputOption(
            $parameter->getName(),
            null,
            $this->buildOptionMode($parameter),
            ''
        );

        $type = $parameter->getType();
        $type = $type ? $type->getName() : null;
        if ($parameter->isDefaultValueAvailable() && $type !== 'bool') {
            $option->setDefault($parameter->getDefaultValue());
        }

        return $option;
    }

    private function buildOptionMode(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();
        $type = $type ? $type->getName() : null;
        if ($type === 'bool') {
            return InputOption::VALUE_NONE;
        }

        return InputOption::VALUE_REQUIRED;
    }
}
