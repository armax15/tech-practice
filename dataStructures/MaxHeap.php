<?php

namespace ds;

use InvalidArgumentException;
use RuntimeException;

class MaxHeap
{

    private array $heap;

    private int $size;

    private int $maxSize;

    public function __construct(int $size, int $maxSize, array $initHeap = [])
    {
        if (count($initHeap) > $maxSize) {
            throw new InvalidArgumentException("Initial heap size greater than $maxSize max size.");
        }

        $this->heap = $initHeap;
        $this->size = $size;
        $this->maxSize = $maxSize;
    }

    public function getParent(int $index): ?int
    {
        if ($index === 0) {
            return null;
        }

        $parentIndex = $this->getParentIndex($index);

        return $this->heap[$parentIndex] ?? null;
    }

    public function getParentIndex(int $index): int
    {
        $this->throwIfIndexOutOfBound($index);

        if ($index === 0) {
            return 0;
        }

        return (int) (($index - 1)/2);
    }

    private function throwIfIndexOutOfBound(int $index): void
    {
        if (!$this->isIndexInBound($index)) {
            throw new InvalidArgumentException("Index $index is out of bound.");
        }
    }

    public function getLeftChild(int $index): ?int
    {
        $childIndex = $this->getLeftChildIndex($index);

        return $this->heap[$childIndex] ?? null;
    }

    private function getLeftChildIndex(int $index): int
    {
        $this->throwIfIndexOutOfBound($index);

        return 2 * $index + 1;
    }

    private function getRightChildIndex(int $index): int
    {
        $this->throwIfIndexOutOfBound($index);

        return 2 * $index + 2;
    }

    public function getRightChild(int $index): ?int
    {
        $childIndex = $this->getRightChildIndex($index);

        return $this->heap[$childIndex] ?? null;
    }

    public function getCurrent(int $index): ?int
    {
        if (!$this->isIndexInBound($index)) {
            throw new InvalidArgumentException("Index $index is out of bound.");
        }

        return $this->heap[$index] ?? null;
    }

    public function getLastIndex(): int
    {
        return max(0, count($this->heap) - 1);
    }

    public function siftUp(int $index): void
    {
        $needContinue = true;
        while($index > 0 && $needContinue) {
            $this->swapWithParent($index);
            $index = $this->getParentIndex($index);
            $parent = $this->getParent($index);
            $current = $this->getCurrent($index);
            $needContinue = $parent === null || $parent < $current;
        }
    }

    public function siftDown(int $index): void
    {
        while (true) {
            $leftChildIndex = $this->getLeftChildIndex($index);
            $rightChildIndex = $this->getRightChildIndex($index);

            $maxIndex = $index;
            if (isset($this->heap[$leftChildIndex]) && $this->heap[$leftChildIndex] > $this->heap[$maxIndex]) {
                $maxIndex = $leftChildIndex;
            }
            if (isset($this->heap[$rightChildIndex]) && $this->heap[$rightChildIndex] > $this->heap[$maxIndex]) {
                $maxIndex = $rightChildIndex;
            }

            if ($maxIndex !== $index) {
                $this->swapWithParent($maxIndex);
                $index = $maxIndex;

                continue;
            }

            break;
        }
    }

    private function getRoot(): int
    {
        return $this->heap[0];
    }

    public function getMax(): int
    {
        return $this->getRoot();
    }

    public function extractMax(): int
    {
        $maxValue = $this->getRoot();
        $this->heap[0] = $this->heap[$this->size - 1];
        $this->size--;
        $this->siftDown(0);

        return $maxValue;
    }

    public function insert(int $value): void
    {
        if ($this->size + 1 > $this->maxSize) {
            throw new RuntimeException("Heap is full.");
        }

        $maxIndex = $this->size;
        $this->size++;
        $this->heap[$maxIndex] = $value;
        $this->siftUp($maxIndex);
    }

    public function changePriority(int $index, int $priority): void
    {
        $oldPriority = $this->heap[$index];
        if ($priority > $oldPriority) {
            $this->siftUp($index);
        } else {
            $this->siftDown($index);
        }
    }

    public function remove(int $index): void
    {
        $this->heap[$index] = $this->getRoot() + 1;
        $this->siftUp($index);
        $this->extractMax();
    }

    private function swapWithParent(int $index): void
    {
        $parentIndex = (int) (($index - 1)/2);
        $tmp = $this->heap[$parentIndex];
        $this->heap[$parentIndex] = $this->heap[$index];
        $this->heap[$index] = $tmp;
    }

    public function getHeap(): array
    {
        return $this->heap;
    }
    
    private function isIndexInBound(int $index): bool
    {
        return !($index < 0 || $index > $this->maxSize - 1);
    }
}
