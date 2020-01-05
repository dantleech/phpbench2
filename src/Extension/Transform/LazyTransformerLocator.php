<?php

namespace PhpBench\Extension\Transform;

use PhpBench\Bridge\Extension\AliasedServiceLocator;
use PhpBench\Bridge\Extension\Exception\AliasedServiceNotFound;
use PhpBench\Library\Transform\Exception\TransformerNotFound;
use PhpBench\Library\Transform\Transformer;
use PhpBench\Library\Transform\TransformerLocator;

class LazyTransformerLocator extends AliasedServiceLocator implements TransformerLocator
{
    public function get(string $alias): Transformer
    {
        try {
            return $this->getService($alias);
        } catch (AliasedServiceNotFound $notFound) {
            throw new TransformerNotFound(sprintf(
                'Transformer "%s" not found, known transformers "%s"',
                $notFound->alias(), implode('", "', $notFound->knownAliases())
            ), 0, $notFound);
        }
    }
}
