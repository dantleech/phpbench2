<?php

namespace PhpBench\Library\Output;

use PhpBench\Library\Output\Output;

interface OutputLocator
{
    public function get(string $alias): Output;
}
