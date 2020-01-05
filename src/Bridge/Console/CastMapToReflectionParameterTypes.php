<?php

namespace PhpBench\Bridge\Console;

use Generator;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class CastMapToReflectionParameterTypes
{
    /**
     * @param array<mixed> $options
     * @return array<mixed>
     *
     */
    public function __invoke(ReflectionMethod $method, array $options): array
    {
        $parameters = iterator_to_array($this->parameterMap($method));
        foreach ($options as $optionName => $option) {
            $parameter = $this->getParameter($parameters, $optionName);
            $type = $parameter->getType();
            if (!$type) {
                continue;
            }

            if (!$type instanceof ReflectionNamedType) {
                continue;
            }
            if ($type->getName() === 'int') {
                $options[$optionName] = (int) $options[$optionName];
            }
            if ($type->getName() === 'float') {
                $options[$optionName] = (float) $options[$optionName];
            }
            if ($type->getName() === 'bool') {
                $options[$optionName] = (bool) $options[$optionName];
            }
        }
        return $options;
    }

    /**
     * @return Generator<string, mixed>
     */
    private function parameterMap(ReflectionMethod $method): Generator
    {
        foreach ($method->getParameters() as $parameter) {
            yield $parameter->getName() => $parameter;
        }
    }

    /**
     * @param array<string, ReflectionParameter> $parameters>
     */
    private function getParameter(array $parameters, string $optionName): ReflectionParameter
    {
        if (!isset($parameters[$optionName])) {
            throw new RuntimeException(sprintf(
                'Unknown parameter "%s"',
                $optionName
            ));
        }

        return $parameters[$optionName];
    }
}
