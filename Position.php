<?php

class Position implements Stringable
{
    public function __construct(
        public readonly int $x,
        public readonly int $y
    ) {}

    public function coordinates(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }

    public function __toString(): string
    {
        return implode(',', $this->coordinates());
    }
}