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

function calculateScenicScore(array $grid, int $treeHeight, int $currentRow, int $currentColumn): int
{
    // Up/Down
    $above = array_slice(array_column($grid, $currentColumn), 0, $currentRow);
    $below = array_slice(array_column($grid, $currentColumn), $currentRow + 1);

    // Left/Right
    $left = array_slice($grid[$currentRow], 0, $currentColumn);
    $right = array_slice($grid[$currentRow], $currentColumn + 1);

    $findScore = function (int $height, array $neighbors): int
    {
        $score = 0;
        foreach ($neighbors as $neighbor) {
            if ($neighbor < $height) {
                $score++;
                continue;
            }

            return ++$score;
        }

        return $score;
    };

    return $findScore($treeHeight, array_reverse($above))
        * $findScore($treeHeight, $below)
        * $findScore($treeHeight, array_reverse($left))
        * $findScore($treeHeight, $right);
}

$scenicScores = [];

for ($row = 1; $row < (count($grid) - 1); $row++) {
    for ($column = 1; $column < (count($grid[$row]) - 1); $column++) {
        $scenicScores[] = calculateScenicScore($grid, $grid[$row][$column], $row, $column);
    }
}

// Test: 8 expected
$highestScenicScore = max($scenicScores);
echo "Highest Scenic Score: $highestScenicScore\n";