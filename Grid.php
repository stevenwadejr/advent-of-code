<?php

class Grid
{
    public function __construct(protected array $grid = []) {}

    public function addRow(array $row): void
    {
        $this->grid[] = $row;
    }

    public function walk(callable $callback): void
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $callback($cell, new Position($x, $y), $this);
            }
        }
    }

    public function neighbors(Position $position): array
    {
        $neighborVals = [];
        
        foreach (Neighbor::cases() as $neighbor) {
            $neighborPos = $neighbor->position();
            $checkX = $position->x + $neighborPos->x;
            $checkY = $position->y + $neighborPos->y;
            $neighborVals[$neighbor->value] = $this->grid[$checkY][$checkX] ?? null;
        }

        return $neighborVals;
    }

    public function cell(Position $position): mixed
    {
        return $this->grid[$position->y][$position->x] ?? null;
    }

    public function print(): void
    {
        foreach ($this->grid as $row) {
            foreach ($row as $cell) {
                echo $cell;
            }
            echo "\n";
        }
    }

    public function replaceCell(Position $position, mixed $value): void
    {
        if (!isset($this->grid[$position->y][$position->x])) {
            throw new InvalidArgumentException('Cell position not found in the grid');
        }

        $this->grid[$position->y][$position->x] = $value;
    }
}