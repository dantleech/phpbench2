<?php

namespace PhpBench\Library\Input;

interface InputLocator
{
    public function get(string $alias): Input;
}
