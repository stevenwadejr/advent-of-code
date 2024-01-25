<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

function isGamePossible(array $sets): bool
{
    $limits = [
        'red' => 12,
        'green' => 13,
        'blue' => 14,
    ];

    foreach ($sets as $set) {
        preg_match_all('/([0-9]+) (blue|red|green)/', $set, $matches, PREG_SET_ORDER);
        foreach ($matches as [, $num, $color]) {
            $num = (int) $num;
            if ($num > $limits[$color]) {
                return false;
            }
        }
    }

    return true;
}

foreach ($reader->lines() as $line) {
    preg_match('/Game ([0-9]+):/', $line, $id);
    $gameId = (int) $id[1];
    $line = trim(preg_replace('/Game ([0-9]+):/', '', $line));
    $sets = array_map('trim', explode(';', $line));
    if (isGamePossible($sets)) {
        $sum += $gameId;
    }
};

echo "Sum = $sum\n";
