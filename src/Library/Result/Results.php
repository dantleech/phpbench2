<?php

namespace PhpBench\Library\Result;

final class Results
{
    /**
     * @var array<Result>
     */
    private $results;

    private function __construct(Result ...$results)
    {
        $this->results = $results;
    }

    public static function many(Result ...$results): self
    {
        return new self(...$results);
    }

    public static function one(Result $result): self
    {
        return new self($result);
    }

    /**
     * @return array<string,array>
     */
    public function toArray(): array
    {
        $map = [];
        foreach ($this->results as $result) {
            $map[$result->name()] = $result->value();
        }
        return $map;
    }
}
