<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

$rules = [];
$comeBefore = [];
$comeAfter = [];

$updates = [];

foreach ($reader->lines() as $line) {
    if (preg_match('/([0-9]+)\|([0-9]+)/', $line, $matches)) {
        $leftRule = (int) $matches[1];
        $rightRule = (int) $matches[2];
        $comeBefore[$leftRule][] = $rightRule;
        $comeAfter[$rightRule][] = $leftRule;
    } elseif (str_contains($line, ',')) {
        $updates[] = array_map('intval', explode(',', $line));
    }
}

$middles = [];

foreach ($updates as $update) {
    $isRightOrder = true;
    foreach ($update as $index => $value) {
        $beforeBits = array_slice($update, 0, $index);
        $afterBits = array_slice($update, $index + 1);

        if (!empty($beforeBits) && !empty(array_intersect($beforeBits, $comeBefore[$value] ?? []))) {
            $isRightOrder = false;
            break;
        }

        if (!empty($afterBits) && !empty(array_intersect($afterBits, $comeAfter[$value] ?? []))) {
            $isRightOrder = false;
            break;
        }
    }

    if ($isRightOrder) {
        $midIndex = floor(count($update) / 2);
        $middles[] = $update[$midIndex];
    }
}

echo "Sum: " . array_sum($middles) . "\n";
