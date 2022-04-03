<?php

namespace tests\dataStructures;

use \ds\MaxHeap;
use PHPUnit\Framework\TestCase;

class MaxHeapTest extends TestCase
{

    public function getUnsortedArray(): array
    {
        return [
            [
                [2, 12, 1, 6, 0, 56, 4, 12, 3, 74, 21, 10, 2, 5, 89],
                [89, 56, 74, 3, 21, 2, 10, 2, 0, 1, 12, 12, 4, 6, 5, 3]
            ]
        ];
    }

    /**
     * @dataProvider getUnsortedArray
     */
    public function testBuildingMaxHeap(array $unsortedArray, array $expected): void
    {
        $size = count($unsortedArray);
        $maxHeap = new MaxHeap(0, $size + 1);
        foreach ($unsortedArray as $value) {
            $maxHeap->insert($value);
        }
        $valueForSortingHeap = PHP_INT_MAX;
        $maxHeap->insert($valueForSortingHeap);
        $lastIndex = $maxHeap->getLastIndex();
        $maxHeap->siftUp($lastIndex);

        $root = $maxHeap->extractMax();
        $this->assertSame($valueForSortingHeap, $root, "Max value extracted from maxHeap [{$root}] != [{$valueForSortingHeap}].");
        $msg = sprintf(
            "Heap [%s] has unexpected order [%s]",
            implode(',', $expected),
            implode(',', $expected)
        );
        $this->assertSame($expected, $maxHeap->getHeap(), $msg);
    }

    /**
     * @dataProvider getUnsortedArray
     */
    public function testExtractingMax(array $unsortedArray): void
    {
        $size = count($unsortedArray);
        $max = max($unsortedArray);
        $maxHeap = new MaxHeap(0, $size + 1);
        foreach ($unsortedArray as $value) {
            $maxHeap->insert($value);
        }

        $root = $maxHeap->getMax();
        $this->assertSame($max, $root, "Max value extracted from maxHeap [{$root}] != [{$max}].");
    }

}