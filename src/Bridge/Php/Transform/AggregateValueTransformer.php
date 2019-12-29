<?php

namespace PhpBench\Bridge\Php\Transform;

use PhpBench\Library\Transform\Transformer;
use RuntimeException;

class AggregateValueTransformer implements Transformer
{
    public function __invoke(array $data, array $groupBy = [], string $valuePath)
    {
        $aggregated = [];
        foreach ($data as $key => $row) {
            $groupKeys = [];
            foreach ($groupBy as $groupKey) {

                if (!isset($row[$groupKey])) {
                    throw new RuntimeException(sprintf(
                        'Grouping key "%s" does not exist in data with keys "%s"',
                        $groupKey, implode('", "', array_keys($data))
                    ));
                }
                // TODO: check value is scalar or normalize
                $groupKeys[] = $row[$groupKey];
                unset($row[$groupKey]);
            }
            $groupKey = implode('-', $groupKeys);

            if (!isset($aggregated[$groupKey])) {
                $aggregated[$groupKey] = [];
            }

            $aggregated[$groupKey][] = $row;
        }

        $valuePath = explode('.', $valuePath);

        $data = array_map(function ($groupedData) use ($valuePath) {
            return array_map(function ($data) use ($valuePath) {
                foreach ($valuePath as $key) {
                    if (!isset($data[$key])) {
                        throw new RuntimeException(sprintf(
                            'Key "%s" does not exist in array with keys "%s"',
                            $key, implode('", "', array_keys($data))
                        ));
                    }
                    $data = $data[$key];
                }
                return $data;
            }, $groupedData);
        }, $aggregated);


        return array_map(function (array $values) {
            return array_sum($values) / count($values);
        }, $data);
    }
}
