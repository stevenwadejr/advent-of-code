<?php

require_once __DIR__ . '/../common.php';

define('TOILET_PAPER', '@');

$grid = new Grid();

foreach ($reader->lines() as $line) {
    $grid->addRow(str_split($line));
}

$totalAccessible = 0;

$grid->walk(function (string $val, Position $cell, Grid $grid) use (&$totalAccessible) {
    if ($val !== TOILET_PAPER) {
        return;
    }

    $neighbors = $grid->neighbors($cell);
    $toiletPaperCells = array_filter(
        $neighbors,
        fn ($cell) => $cell === TOILET_PAPER
    );
    
    if (count($toiletPaperCells) < 4) {
        $totalAccessible++;
    }
});

// $grid->print();

echo "Total accessible cells = $totalAccessible\n";