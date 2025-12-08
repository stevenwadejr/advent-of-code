<?php

require_once __DIR__ . '/../common.php';

define('STARTER_VAL', 'S');
define('SPLIT_VAL', '|');
define('EMPTY_VAL', '.');
define('SPLITTER_VAL', '^');

$exampleExpected = 40;

$grid = new Grid();

$counts = [];
$totalTimelines = 0;

foreach ($reader->lines() as $line) {
    $grid->addRow(str_split($line));
}

$grid->walk(function ($cell, Position $position, Grid $grid) use (&$counts, &$totalTimelines) {
    if ($cell->value === STARTER_VAL) {
        $counts[$position->y][$position->x] = 1;
        // return;
    }

    $k = $counts[$position->y][$position->x] ?? 0;
    if ($k === 0) {
        return;
    }

    $neighbors = $grid->neighbors($cell->position);
    $below = $neighbors[Neighbor::S->value];
    if (!$below) {
        $totalTimelines += $k;
        return;
    }

    if ($below->value === EMPTY_VAL) {
        $counts[$below->position->y][$position->x] ??= 0;
        $counts[$below->position->y][$position->x] += $k;
    } elseif ($below->value === SPLITTER_VAL) {
        $SW = $neighbors[Neighbor::SW->value];
        if (!$SW) {
            $totalTimelines += $k;
        } elseif ($SW->value === EMPTY_VAL) {
            $counts[$SW->position->y][$SW->position->x] ??= 0;
            $counts[$SW->position->y][$SW->position->x] += $k;
        }

        $SE = $neighbors[Neighbor::SE->value];
        if (!$SE) {
            $totalTimelines += $k;
        } elseif ($SE->value === EMPTY_VAL) {
            $counts[$SE->position->y][$SE->position->x] ??= 0;
            $counts[$SE->position->y][$SE->position->x] += $k;
        }
    }
});

$lastRow = $grid->numRows();
for ($x = 0; $x < $grid->numCols(); $x++) {
    $totalTimelines += $counts[$lastRow][$x] ?? 0;
}

// print_r($counts);

echo "Total timelines = $totalTimelines\n";