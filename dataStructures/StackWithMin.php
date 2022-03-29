<?php

namespace dataStructures;

class StackWithMin
{

    private array $stack = [];

    public function isEmpty(): bool
    {
        return empty($this->stack);
    }

    public function push(int $value): void
    {
        $currentMin = PHP_INT_MAX;
        if (!$this->isEmpty()) {
           $currentMin = $this->stack[$this->getLastIndex()]['min'];
        }
        $newMin = min($value, $currentMin);
        $this->stack[] = ['value' => $value, 'min' => $newMin];
    }

    public function pop(): ?int
    {
        if ($this->isEmpty()) {
            return null;
        }

        $lastIndex = $this->getLastIndex();
        $top = $this->stack[$lastIndex];
        unset($this->stack[$lastIndex]);

        return $top['value'];
    }

    public function getMin(): ?int
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->stack[$this->getLastIndex()]['min'];
    }

    private function getLastIndex(): int
    {
        if (empty($this->stack)) {
            return 0;
        }

        if (function_exists('array_key_last')) {
            return array_key_last($this->stack);
        }

        $keys = array_keys($this->stack);

        return $keys[count($this->stack) - 1];
    }
}