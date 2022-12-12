<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/8.txt');

$grid = [];
$row = 0;

$reader->loop(function ($line) use (&$grid, &$row) {
    $grid[$row] = str_split($line);
    $row++;   
});

$gridWidth = count($grid[0]);
$gridHeight = count($grid);
$totalVisible = ($gridWidth * 2) + ($gridHeight * 2) - 4; // -4 because it'll count the corners twice

function isVisible(array $grid, int $treeHeight, int $currentRow, int $currentColumn): bool
{
    // Up/Down
    $above = array_slice(array_column($grid, $currentColumn), 0, $currentRow);
    $below = array_slice(array_column($grid, $currentColumn), $currentRow + 1);

    // Left/Right
    $left = array_slice($grid[$currentRow], 0, $currentColumn);
    $right = array_slice($grid[$currentRow], $currentColumn + 1);

    return (max($above) < $treeHeight)
        || (max($below) < $treeHeight)
        || (max($left) < $treeHeight)
        || (max($right) < $treeHeight);
}

for ($row = 1; $row < (count($grid) - 1); $row++) {
    for ($column = 1; $column < (count($grid[$row]) - 1); $column++) {
        if (isVisible($grid, $grid[$row][$column], $row, $column)) {
            $totalVisible++;
        }
    }
}

// Test: 21 expected
echo "Total visible: $totalVisible\n";