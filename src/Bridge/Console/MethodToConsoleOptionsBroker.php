<?php

namespace PhpBench\Bridge\Console;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

final class MethodToConsoleOptionsBroker
{
    /**
     * @var ReflectionMethod
     */
    private $method = null;

    /**
     * @param class-string $className
     */
    public function __construct(string $className, string $methodName)
    {
        $reflection = new ReflectionClass($className);

        if (!$reflection->hasMethod($methodName)) {
            return;
        }

        $this->method = $reflection->getMethod($methodName);
    }

    public function inputDefinition(): InputDefinition
    {
        $definition = new InputDefinition();

        if (null === $this->method) {
            return $definition;
        }

        foreach ($this->method->getParameters() as $parameter) {
            $definition->addOption($this->createOption($parameter));
        }

        return $definition;
    }

    /**
     * @param array<mixed> $options
     * @return array<mixed>
     */
    public function castOptions(array $options): array
    {
        return (new CastMapToReflectionParameterTypes())->__invoke($this->method, $options);
    }

    private function createArgument(ReflectionParameter $parameter): InputArgument
    {
        return new InputArgument($parameter->getName(), InputArgument::REQUIRED);
    }

    private function createOption(ReflectionParameter $parameter): InputOption
    {
        $option = new InputOption(
            $parameter->getName(),
            null,
            (int)$this->buildOptionMode($parameter),
            ''
        );

        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            return $option;
        }

        $type = $type->getName();

        if ($parameter->isDefaultValueAvailable() && !in_array($type, ['bool','array'])) {
            $option->setDefault($parameter->getDefaultValue());
        }

        return $option;
    }

    private function buildOptionMode(ReflectionParameter $parameter): int
    {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            return InputOption::VALUE_OPTIONAL;
        }

        $type = $type->getName();

        if ($type === 'array') {
            return InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED;
        }

        if ($type === 'bool') {
            return InputOption::VALUE_NONE;
        }

        return InputOption::VALUE_REQUIRED;
    }
}
