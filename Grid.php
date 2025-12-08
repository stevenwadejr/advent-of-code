<?php

class Grid
{
    public function __construct(protected array $grid = []) {}

    public function column(int $colNum): array
    {
        if ($colNum <= 0 || $colNum > count($this->grid[0] ?? [])) {
            throw new InvalidArgumentException('Column number is out of bounds');
        }

        $column = [];
        foreach ($this->grid as $row) {
            $column[] = $row[$colNum - 1];
        }

        return $column;
    }

    public function columns(bool $stream = true): Generator|array
    {
        $columns = [];
        for ($i = 1; $i <= count($this->grid[0]); $i++) {
            if ($stream) {
                yield $this->column($i);
            } else {
                $columns[] = $this->column($i);
            }
        }

        if (!$stream) {
            return $columns;
        }
    }

    public function addRow(array $row): void
    {
        $newRowIndex = count($this->grid);
        $toInsert = [];
        
        foreach ($row as $index => $val) {
            $toInsert[] = $val instanceof Cell
                ? $val
                : new Cell(
                    $val,
                    new Position($index, $newRowIndex)
                );
        }

        $this->grid[] = $toInsert;
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

        $this->grid[$position->y][$position->x]->value = $value;
    }

    public function numRows(): int
    {
        return count($this->grid);
    }

    public function numCols(): int
    {
        return count($this->grid[0] ?? []);
    }
}