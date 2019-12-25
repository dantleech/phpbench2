<?php

namespace PhpBench\Bridge\Console;

use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

final class CastMapToReflectionParameterTypes
{
    public function __invoke(ReflectionMethod $method, array $options)
    {
        $parameters = iterator_to_array($this->parameterMap($method));
        foreach ($options as $optionName => $option) {
            $parameter = $this->getParameter($parameters, $optionName);
            $type = $parameter->getType();
            if (!$type) {
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

    private function parameterMap(ReflectionMethod $method)
    {
        foreach ($method->getParameters() as $parameter) {
            yield $parameter->getName() => $parameter;
        }
    }

    private function getParameter(array $parameters, $optionName): ReflectionParameter
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
