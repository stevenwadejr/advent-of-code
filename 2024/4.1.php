<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

$text = '';
$grid = [];
foreach ($reader->lines() as $line) {
    $text .= $line;
    $grid[] = str_split($line);
}


function hasAhead(array $grid, $rowIdx, $colIdx): bool
{
    $str = '';

    for ($i = 0; $i < 4; $i++) {
        $str .= $grid[$rowIdx][$colIdx + $i] ?? '';
    }

    return $str === 'XMAS';
}

function hasBackward(array $grid, $rowIdx, $colIdx): bool
{
    $str = '';

    for ($i = 0; $i < 4; $i++) {
        $str .= $grid[$rowIdx][$colIdx - $i] ?? '';
    }

    return $str === 'XMAS';
}

function hasUp(array $grid, $rowIdx, $colIdx): bool
{
    $str = '';

    for ($i = 0; $i < 4; $i++) {
        $str .= $grid[$rowIdx - $i][$colIdx] ?? '';
    }

    return $str === 'XMAS';
}

function hasDown(array $grid, $rowIdx, $colIdx): bool
{
    $str = '';

    for ($i = 0; $i < 4; $i++) {
        $str .= $grid[$rowIdx + $i][$colIdx] ?? '';
    }

    return $str === 'XMAS';
}

function countDiagonal(array $grid, $rowIdx, $colIdx): int
{
    $results = [
        '↖' => '',
        '↗' => '',
        '↘' => '',
        '↙' => ''
    ];

    for ($i = 0; $i < 4; $i++) {
        $results['↖'] .= $grid[$rowIdx - $i][$colIdx - $i] ?? '';
        $results['↗'] .= $grid[$rowIdx - $i][$colIdx + $i] ?? '';
        $results['↘'] .= $grid[$rowIdx + $i][$colIdx + $i] ?? '';
        $results['↙'] .= $grid[$rowIdx + $i][$colIdx - $i] ?? '';
    }

    return count(array_filter($results, fn($value) => $value === 'XMAS'));
}

foreach ($grid as $rowIdx => $row) {
    foreach ($row as $colIdx => $char) {
        if ($char !== 'X') {
            continue;
        }

        $functions = [
            'hasAhead',
            'hasBackward',
            'hasUp',
            'hasDown',
        ];

        foreach ($functions as $func) {
            if ($func($grid, $rowIdx, $colIdx)) {
                $sum++;
            }
        }

        $sum += countDiagonal($grid, $rowIdx, $colIdx);
    }
}

echo "Sum: $sum\n";
