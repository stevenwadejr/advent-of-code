<?php

require_once __DIR__ . '/../common.php';

$grid = new Grid();

foreach ($reader->lines() as $line) {
    if (preg_match_all('/[0-9]+/', $line, $numbers)) {
        $grid->addRow(
            array_map('intval', $numbers[0])
        );
    } elseif (preg_match_all('/[\*\+]/', $line, $operations)) {
        $grid->addRow($operations[0]);
    }
}

$grandTotal = 0;

foreach ($grid->columns() as $column) {
    $operation = array_pop($column);
    $grandTotal += match ($operation) {
        '+' => array_sum($column),
        '*' => array_product($column),
        default => 0
    };
}

echo "Grand Total = $grandTotal\n";