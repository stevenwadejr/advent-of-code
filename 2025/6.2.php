<?php

require_once __DIR__ . '/../common.php';

$grid = new Grid();

$rawRows = [];
$operationsRaw = [];

foreach ($reader->lines(false) as $line) {
    if (preg_match_all('/[0-9]+/', $line, $numbers)) {
        $rawRows[] = $line;
    } elseif (preg_match_all('/([*+]\s*)/', $line, $matches)) {
        $operationsRaw = $matches[1];
    }
}

// Create cells using column width and add row to the grid
$lastOperationsRawKey = array_key_last($operationsRaw);
foreach ($rawRows as $rawRow) {
    $row = [];
    $offset = 0;
    foreach ($operationsRaw as $index => $col) {
        $colWidth = strlen($col);
        if ($index !== $lastOperationsRawKey) {
            $colWidth--;
        }

        $row[] = substr($rawRow, $offset, $colWidth);
        $offset += $colWidth + 1;
    }

    $grid->addRow($row);
}

// Add operations row to the grid
$grid->addRow(array_map('trim', $operationsRaw));

function numDigits(int $number): int
{
    return strlen((string) $number);
}

$grandTotal = 0;

foreach ($grid->columns() as $column) {
    /*
        A column at this point looks like this:
          123
           45
            6
          *
        
        So we get the last row and that's our operator.

        then we turn the 3 remaining rows into its own subgrid.
     */
    $operation = array_pop($column);

    $subGrid = new Grid();
    foreach ($column as $row) {
        $subGrid->addRow(str_split($row));
    }

    $numbers = [];

    /*
        The subgrid's columns will now be "1  ", "24 ", and "356".
        We turn those into ints so we can continue our cumulative operation.
     */
    foreach ($subGrid->columns() as $subColumn) {
        $numbers[] = (int) trim(implode('', $subColumn));
    }

    $grandTotal += match ($operation) {
        '+' => array_sum($numbers),
        '*' => array_product($numbers),
        default => 0
    };
}

echo "Grand Total = $grandTotal\n";