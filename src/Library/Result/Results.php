<?php

namespace PhpBench\Library\Result;

final class Results
{
    /**
     * @var array
     */
    private $results;

    private function __construct(Result ...$results)
    {
        $this->results = $results;
    }

    public static function many(...$results): self
    {
        return new self(...$results);
    }

    public static function one(Result $result)
    {
        return new self($result);
    }

    public function toArray(): array
    {
        $map = [];
        foreach ($this->results as $result) {
            $map[$result->name()] = $result->toArray();
        }
        return $map;
    }
}
