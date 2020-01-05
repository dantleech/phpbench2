<?php

namespace PhpBench\Library\DataStructure;

final class DataFactory
{
    public function for(array $data): Data
    {
        if (empty($data)) {
            return new Nothing();
        }

        if (DataStructureAssert::isList($data)) {
            return $this->forList($data);
        }

        return $this->forMap($data);
    }

    private function forList(array $data): Data
    {
        if (DataStructureAssert::areValuesNumeric($data)) {
            return new NumericList($data);
        }

        if (DataStructureAssert::areValuesNumericArrays($data)) {
            return new NumericMapList($data);
        }

        if (DataStructureAssert::areValuesArrays($data)) {
            return new MixedMapList($data);
        }

        return new MixedList($data);
    }

    private function forMap(array $data): Data
    {
        if (DataStructureAssert::areValuesNumeric($data)) {
            return new NumericMap($data);
        }

        return new MixedMap($data);
    }
}
