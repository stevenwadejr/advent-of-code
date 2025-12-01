<?php

require_once __DIR__ . '/../common.php';

// column, row
$startingPos = [0, 0];
$grid = [];
$visited = [];
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

$canMove = true;
$direction = Direction::Up;
[$currentCol, $currentRow] = $startingPos;
while ($canMove) {
    $visited[] = coordsToString($currentCol, $currentRow);
    $newCol = $currentCol + $direction->coords()[0];
    $newRow = $currentRow + $direction->coords()[1];

    if (!isset($grid[$newRow][$newCol])) {
        $canMove = false;
        continue;
    }

    if ($grid[$newRow][$newCol] === '#') {
        $direction = $direction->turn();
        continue;
    }

    $currentCol = $newCol;
    $currentRow = $newRow;
}

$visited = array_values(array_unique($visited));

echo 'Distinct positions: ' . count($visited) . "\n";
