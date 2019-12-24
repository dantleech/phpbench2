<?php

namespace PhpBench\Library\Result;

class Results
{
    /**
     * @var array
     */
    private $results;

    private function __construct(Result ...$results)
    {
        $this->results = $results;
    }

    public static function many(array $results): self
    {
        return new self(...$results);
    }

    public static function one(Result $result)
    {
        return new self($result);
    }
}
