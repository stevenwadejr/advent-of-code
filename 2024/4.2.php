<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

$grid = [];
foreach ($reader->lines() as $line) {
    $grid[] = str_split($line);
}


function checkDiagonal(array $grid, $rowIdx, $colIdx): bool
{
    $topLeft = $grid[$rowIdx - 1][$colIdx - 1] ?? '';
    $topRight = $grid[$rowIdx - 1][$colIdx + 1] ?? '';
    $bottomRight = $grid[$rowIdx + 1][$colIdx + 1] ?? '';
    $bottomLeft = $grid[$rowIdx + 1][$colIdx - 1] ?? '';

    $result = [
        '↙↗' => $bottomLeft . 'A' . $topRight,
        '↖↘' => $topLeft . 'A' . $bottomRight,
    ];

    return ($result['↙↗'] === 'MAS' || $result['↙↗'] === 'SAM') &&
        ($result['↖↘'] === 'MAS' || $result['↖↘'] === 'SAM');
}

foreach ($grid as $rowIdx => $row) {
    foreach ($row as $colIdx => $char) {
        if ($char === 'A' && checkDiagonal($grid, $rowIdx, $colIdx)) {
            $sum++;
        }
    }
}

echo "Sum: $sum\n";
