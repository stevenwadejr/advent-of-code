<?php

require_once __DIR__ . '/../common.php';

// column, row
$startingPos = [0, 0];
$grid = [];
$rowIdx = 0;

enum Direction
{
    case Up;
    case Down;
    case Left;
    case Right;

    public function turn(): self
    {
        return match ($this) {
            self::Up => self::Right,
            self::Right => self::Down,
            self::Down => self::Left,
            self::Left => self::Up,
        };
    }

    public function coords(): array
    {
        return match ($this) {
            self::Up => [0, -1],
            self::Right => [1, 0],
            self::Down => [0, 1],
            self::Left => [-1, 0],
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::Up => 'U',
            self::Right => 'R',
            self::Down => 'D',
            self::Left => 'L',
        };
    }
}

enum Outcome
{
    case InfiniteLoop;
    case Escaped;
}

function coordsToString(int $col, int $row): string
{
    return $col . ',' . $row;
}

foreach ($reader->lines() as $line) {
    $grid[] = str_split($line);
    $startingPosIdx = strpos($line, '^');
    if ($startingPosIdx !== false) {
        $startingPos = [$startingPosIdx, $rowIdx];
    }

    $rowIdx++;
}

$dots = substr_count($reader->wholeFile(), '.');
echo "Dots: $dots\n";

function walkMaze(array $grid, array $startingPos, array $obstruction): Outcome
{
    $grid[$obstruction[1]][$obstruction[0]] = '#';
    $canMove = true;
    $direction = Direction::Up;
    [$currentCol, $currentRow] = $startingPos;
    $visited = [];
    while ($canMove) {
        $location = coordsToString($currentCol, $currentRow) . ',' . $direction->toString();
        $visited[$location] = ($visited[$location] ?? 0) + 1;
        $newCol = $currentCol + $direction->coords()[0];
        $newRow = $currentRow + $direction->coords()[1];

        if (
            $visited[$location] > 1
        ) {
            echo "infinite loop found\n";
            return Outcome::InfiniteLoop;
        }

        if (!isset($grid[$newRow][$newCol])) {
            return Outcome::Escaped;
        }

        if ($grid[$newRow][$newCol] === '#') {
            $direction = $direction->turn();
            continue;
        }

        $currentCol = $newCol;
        $currentRow = $newRow;
    }
}

$obstructions = 0;
foreach ($grid as $rowIdx => $row) {
    foreach ($row as $colIdx => $col) {
        if (
            $col === '.'
            && walkMaze($grid, $startingPos, [$colIdx, $rowIdx]) === Outcome::InfiniteLoop
        ) {
            $obstructions++;
        }
    }
}

echo "Obstruction total: $obstructions\n";