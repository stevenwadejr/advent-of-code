<?php

require_once __DIR__ . '/../common.php';

define('TOILET_PAPER', '@');
define('REMOVED', 'x');

$grid = new Grid();

foreach ($reader->lines() as $line) {
    $grid->addRow(str_split($line));
}

$totalRemoved = 0;

$canRemoveMore = true;
while ($canRemoveMore) {
    $toRemove = [];
    $grid->walk(function (string $val, Position $cell, Grid $grid) use (&$totalRemoved, &$toRemove) {
        if ($val !== TOILET_PAPER) {
            return;
        }
    
        $neighbors = $grid->neighbors($cell);
        $toiletPaperCells = array_filter(
            $neighbors,
            fn ($cell) => $cell === TOILET_PAPER
        );
        
        if (count($toiletPaperCells) < 4) {
            $totalRemoved++;
            $toRemove[] = $cell;
        }
    });

    if (empty($toRemove)) {
        $canRemoveMore = false;
        continue;
    }

    foreach ($toRemove as $pos) {
        $grid->replaceCell($pos, REMOVED);
    }
}

echo "Total accessible cells = $totalRemoved\n";