<?php

namespace PhpBench\Bridge\Console;

use Symfony\Component\Console\Input\StringInput;

final class CliParametersToInvokableParameters
{
    /**
     * @return array<string, mixed>
     */
    public static function convert(object $invokable, array $rawParameters): array
    {
        $input = new StringInput(implode(
            ' ',
            $rawParameters
        ));

        $converter = new MethodToConsoleOptionsBroker(get_class($invokable), '__invoke');

        $input->bind($converter->inputDefinition());
        $options = $converter->castOptions($input->getOptions());
        return $options;
    }
}
