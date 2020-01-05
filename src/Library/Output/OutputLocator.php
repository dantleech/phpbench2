<?php

namespace PhpBench\Library\Output;

interface OutputLocator
{
    public function get(string $alias): Output;
}
