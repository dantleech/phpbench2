<?php

namespace PhpBench\Bridge\Php\Visualizer;

use PhpBench\Library\Visualize\Visualizer;
use IntlChar;

class AnsiBarChartVisualizer implements Visualizer
{
    const PADDING = 1;

    /**
     * @param array<float|int> $values
     */
    public function __invoke(array $values, int $width = 50): string
    {
        return $this->graph($values, $width);
    }

    /**
     * @param array<float|int> $values
     */
    private function graph(array $values, int $maxWidth): string
    {
        $graph = [];
        $maxValue = max($values);
        $barWidth = $this->barWidth($maxValue, $maxValue, $maxWidth);
        $labelWidth = $this->maxLabelWidth($values);

        foreach ($values as $key => $value) {
            $graph[] = sprintf(
                '%-' . $labelWidth . 's |%s %s',
                $key,
                $this->pad($this->bar($value, $maxValue, $maxWidth), $barWidth),
                number_format($value, 6)
            );
        }

        return implode(PHP_EOL, $graph) . PHP_EOL;
    }

    private function barWidth(float $max, float $current, int $maxWidth): int
    {
        if ($max == 0) {
            return (int)$max;
        }

        return (int)ceil(($current / $max) * $maxWidth);
    }

    private function maxLabelWidth(array $values): int
    {
        $max = 0;

        foreach (array_keys($values) as $value) {
            $length = mb_strlen((string)$value);
            if ($length > $max) {
                $max = $length;
            }
        }

        return $max + self::PADDING;
    }

    private function bar(float $value, float $maxValue, int $maxWidth): string
    {
        if ($maxValue == 0) {
            return '';
        }

        // fill solid section
        $char = mb_chr(0x2588);
        $barWidth = $this->barWidth($maxValue, $value, $maxWidth);
        $bar = '';

        if ($barWidth == 0) {
            return $bar;
        }

        // draw solid segments excepting the last one
        if ($barWidth > 1) {
            $bar .= str_repeat($char,  $barWidth - 1);
        }

        // determine final segments char
        $stepValue = $maxValue / $maxWidth;
        $remainderValue = $value - ($stepValue * floor($value / $stepValue)) ;

        // perfect fit, full segment
        if (0 == $remainderValue) {
            return $bar . $char;
        }

        $fraction = $remainderValue / $stepValue;
        $offset = (8 - ((int) floor(8 * $fraction))) % 8;

        // 0th offset is blank
        if ($offset === 0) {
            return $bar;
        }

        $char = hexdec(2588) + $offset;
        $bar .= mb_chr($char);

        return $bar;
    }

    private function pad(string $string, int $length): string
    {
        $width = $length - mb_strlen($string);

        return $string.str_repeat(' ', $width);
    }
}
