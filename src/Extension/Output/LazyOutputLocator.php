<?php

namespace PhpBench\Extension\Output;

use PhpBench\Bridge\Extension\AliasedServiceLocator;
use PhpBench\Bridge\Extension\Exception\AliasedServiceNotFound;
use PhpBench\Library\Input\Input;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Output\Exception\OutputNotFound;
use PhpBench\Library\Output\Output;
use PhpBench\Library\Output\OutputLocator;

class LazyOutputLocator extends AliasedServiceLocator implements OutputLocator
{
    public function get(string $alias): Output
    {
        try {
            return $this->getService($alias);
        } catch (AliasedServiceNotFound $notFound) {
            throw new OutputNotFound(sprintf(
                'Input with alias "%s" not found, known inputs "%s"',
                $notFound->alias(), implode('", "', $notFound->knownAliases())
            ), 0, $notFound);
        }
    }
}
