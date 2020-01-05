<?php

namespace PhpBench\Library\Visualize;

use DTL\Invoke\Invoke;
use PhpBench\Library\Input\InputConfig;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Input\Stream;
use PhpBench\Library\Output\OutputConfig;
use PhpBench\Library\Output\OutputLocator;

class Visualizer
{
    /**
     * @var RendererLocator
     */
    private $rendererLocator;

    /**
     * @var WriterLocator
     */
    private $writerLocator;

    /**
     * @var InputLocator
     */
    private $inputLocator;

    public function __construct(
        RendererLocator $rendererLocator,
        InputLocator $inputLocator,
        OutputLocator $writerLocator
    )
    {
        $this->rendererLocator = $rendererLocator;
        $this->writerLocator = $writerLocator;
        $this->inputLocator = $inputLocator;
    }

    public function visualize(
        InputConfig $inputConfig,
        OutputConfig $writerConfig,
        RendererConfig $rendererConfig
    ): string
    {
        $input = $this->inputLocator->get($inputConfig->alias());
        $writer = $this->writerLocator->get($writerConfig->alias());
        $renderer = $this->rendererLocator->get($rendererConfig->name());

        $stream = Invoke::method($input, 'open', $inputConfig->params());
        assert($stream instanceof Stream);

        while ($data = $stream->readLine()) {
            $rendered = Invoke::method($renderer, array_merge([
                'data' => $data,
            ], $rendererConfig->params()));

            Invoke::method($writer, array_merge([
                'data' => $rendered,
            ], $writerConfig->params()));
        }

        $stream->close();
    }
}
