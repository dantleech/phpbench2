<?php

namespace PhpBench\Extension\Input;

use PhpBench\Bridge\Extension\AliasedServiceLocator;
use PhpBench\Bridge\Extension\Exception\AliasedServiceNotFound;
use PhpBench\Library\Input\Exception\InputNotFound;
use PhpBench\Library\Input\Input;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Output\Output;

class LazyInputLocator extends AliasedServiceLocator implements InputLocator
{
    public function get(string $alias): Input
    {
        try {
            return $this->getService($alias);
        } catch (AliasedServiceNotFound $notFound) {
            throw new InputNotFound(sprintf(
                'Input with alias "%s" not found, known inputs "%s"',
                $notFound->alias(), implode('", "', $notFound->knownAliases())
            ), 0, $notFound);
        }
    }
}
