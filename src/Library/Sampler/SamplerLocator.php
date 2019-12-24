<?php

namespace PhpBench\Library\Sampler;

interface SamplerLocator
{
    public function get(string $alias): Sampler;
}
