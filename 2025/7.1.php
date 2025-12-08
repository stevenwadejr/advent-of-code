<?php

require_once __DIR__ . '/../common.php';

define('SPLIT_VAL', '|');
define('EMPTY_VAL', '.');
define('SPLITTER_VAL', '^');

$exampleExpected = 21;

$timesSplit = 0;

$grid = new Grid();

foreach ($reader->lines() as $line) {
    $grid->addRow(str_split($line));
}

$grid->walk(function ($cell, Position $position) use (&$timesSplit, $grid) {
    if ($cell->value === 'S') {
        $southNeighbor = $grid->neighbors($position)[Neighbor::S->value];
        $grid->replaceCell($southNeighbor->position, SPLIT_VAL);
    } elseif ($cell->value === SPLITTER_VAL) {
        $neighbors = $grid->neighbors($cell->position);
        $left = $neighbors[Neighbor::W->value];
        $right = $neighbors[Neighbor::E->value];
        $top = $neighbors[Neighbor::N->value];

        if ($top?->value === SPLIT_VAL) {
            $timesSplit++;
        }
        
        if ($left?->value === EMPTY_VAL) {
            $grid->replaceCell($left->position, SPLIT_VAL);
        }

        if ($right?->value === EMPTY_VAL) {
            $grid->replaceCell($right->position, SPLIT_VAL);
        }

    } elseif ($cell->value === EMPTY_VAL) {
        $top = $grid->neighbors($position)[Neighbor::N->value];
        if ($top?->value === SPLIT_VAL) {
            $grid->replaceCell($cell->position, SPLIT_VAL);
        }
    }
});

// $grid->print();

echo "Number of times tachyon beam was split = $timesSplit\n";