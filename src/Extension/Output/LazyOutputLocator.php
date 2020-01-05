<?php

namespace PhpBench\Extension\Output;

use PhpBench\Bridge\Extension\AliasedServiceLocator;
use PhpBench\Library\Input\Input;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Output\Output;
use PhpBench\Library\Output\OutputLocator;

class LazyOutputLocator extends AliasedServiceLocator implements OutputLocator
{
    public function get(string $alias): Output
    {
        return $this->getService($alias);
    }
}
