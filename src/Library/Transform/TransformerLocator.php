<?php

namespace PhpBench\Library\Transform;

interface TransformerLocator
{
    public function get(string $alias): Transformer;
}
