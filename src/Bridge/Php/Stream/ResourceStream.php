<?php

namespace PhpBench\Bridge\Php\Stream;

use PhpBench\Library\Stream\Stream;
use PhpBench\Library\Json\JsonDecode;

class ResourceStream implements Stream
{
    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function readData(): array
    {
        return JsonDecode::decode(fgets($this->resource));
    }

    public function close(): void
    {
        fclose($this->resource);
    }

    public function write(string $data): void
    {
        fwrite($this->resource, $data);
    }
}
