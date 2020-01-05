<?php

namespace PhpBench\Library\Visualize;

use DTL\Invoke\Invoke;
use PhpBench\Library\Input\InputConfig;
use PhpBench\Library\Input\InputLocator;
use PhpBench\Library\Stream\Stream;
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
    ) {
        $this->rendererLocator = $rendererLocator;
        $this->writerLocator = $writerLocator;
        $this->inputLocator = $inputLocator;
    }

    public function visualize(
        InputConfig $inputConfig,
        OutputConfig $writerConfig,
        RendererConfig $rendererConfig
    ): void {
        $input = $this->inputLocator->get($inputConfig->alias());
        $writer = $this->writerLocator->get($writerConfig->alias());
        $renderer = $this->rendererLocator->get($rendererConfig->name());

        $in = Invoke::method($input, '__invoke', $inputConfig->params());
        $out = Invoke::method($writer, '__invoke', $writerConfig->params());
        assert($in instanceof Stream);
        assert($out instanceof Stream);

        while ($data = $in->readData()) {
            $rendered = Invoke::method($renderer, '__invoke', array_merge($rendererConfig->params(), [
                'values' => $data,
            ]));

            $out->write($rendered);
        }

        $out->close();
        $in->close();
    }
}
