<?php

class SlidingWindow
{
    private $values = [];

    private $previous = null;

    public function isFull()
    {
        return count($this->values) === 3;
    }

    public function isEmpty()
    {
        return empty($this->values);
    }

    public function insert(int $value): bool
    {
        if ($this->isFull()) {
            return false;
        }

        $this->values[] = $value;
        return true;
    }

    public function sum(): int
    {
        return array_sum($this->values);
    }

    public function getPrevious(): ?SlidingWindow
    {
        return $this->previous;
    }

    public function setPrevious(?SlidingWindow $previous)
    {
        $this->previous = $previous;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}

$handle = fopen(__DIR__ . '/input/1.1.txt', "r");
$last = null;
$sumsIncreased = 0;

$index = 0;
while (($line = fgets($handle)) !== false) {
    $input = (int) trim($line);

    $current = new SlidingWindow();
    $current->insert($input);
    $current->setPrevious($last);

    $last?->insert($input);
    $last?->getPrevious()?->insert($input);

    if (
        $last
        && $last->getPrevious()?->isFull()
        && $last->getPrevious()->getPrevious()?->isFull()
        && $last->getPrevious()->sum() > $last->getPrevious()->getPrevious()->sum()
    ) {
        $sumsIncreased++;
    }

    $last = $current;
}

fclose($handle);

echo "How many sums are larger than the previous sum? $sumsIncreased\n";

echo "done";
