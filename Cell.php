<?php

class Cell implements Stringable
{
    public function __construct(
        public string $value,
        public Position $position
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}