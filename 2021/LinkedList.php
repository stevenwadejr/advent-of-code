<?php

require_once __DIR__ . '/Node.php';

class LinkedList
{
    private ?Node $head = null;

    private ?Node $tail = null;

    private int $size = 0;

    public function __destruct()
    {
        $current = $this->head;

        while ($current !== null) {
            $tempNode = $current;
            $current = $current->next;

            unset($tempNode);
        }
    }

    public function append($data): void
    {
        $newNode = new Node($data);

        if ($this->head === null) {
            $this->head = $newNode;
        } elseif ($this->tail !== null) {
            $this->tail->next = $newNode;
        }

        $this->tail = $newNode;

        // echo "append\n";

        $this->size++;
    }

    public function prepend($data): void
    {
        $newNode = new Node($data);

        if ($this->head !== null) {
            $newNode->next = $this->head;
        }

        $this->head = $newNode;

        $this->size++;
    }

    public function each(): ?Generator
    {
        $lock = $this->tail;
        $current = $this->head;
        while ($current !== null) {
            yield $current;

            if ($current === $lock && $current !== null) {
                return null;
            }

            $current = $current->next;
        }

        return null;
    }

    public function size(): int
    {
        return $this->size;
    }
}
