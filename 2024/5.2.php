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

$isRightOrder = function (array $update) use ($comeBefore, $comeAfter) {
    foreach ($update as $index => $value) {
        $beforeBits = array_slice($update, 0, $index);
        $afterBits = array_slice($update, $index + 1);

        if (!empty($beforeBits) && !empty(array_intersect($beforeBits, $comeBefore[$value] ?? []))) {
            return false;
        }

        if (!empty($afterBits) && !empty(array_intersect($afterBits, $comeAfter[$value] ?? []))) {
            return false;
        }
    }

    return true;
};

$reorder = function (array $update) use ($comeBefore, $comeAfter): array {
    foreach ($update as $index => $value) {
        $beforeBits = array_slice($update, 0, $index);
        $afterBits = array_slice($update, $index + 1);

        // Note: Apparently this section wasn't ever used in Part 2...
        // $beforeIntersect = array_intersect($beforeBits, $comeBefore[$value] ?? []);
        // if (!empty($beforeBits) && !empty($beforeIntersect)) {
        //     echo "Before - $value\n";
        //     print_r($beforeBits);
        //     print_r($beforeIntersect);
        //     die;
        // }

        $afterIntersect = array_intersect($afterBits, $comeAfter[$value] ?? []);
        if (!empty($afterBits) && !empty($afterIntersect)) {
            $whereToSplit = array_search(end($afterIntersect), $update);
            unset($update[$index]);
            return array_merge(
                array_slice($update, 0, $whereToSplit),
                [$value],
                array_slice($update, $whereToSplit)
            );
        }
    }

    throw new RuntimeException('Unable to reorder');
};

$total = count($updates);
$current = 0;

foreach ($updates as $update) {
    echo "Checking " . ++$current . " out of {$total} updates\n";
    if ($isRightOrder($update)) {
        continue;
    }

    while (!$isRightOrder($update)) {
        $update = $reorder($update);
    }

    $midIndex = floor(count($update) / 2);
    $middles[] = $update[$midIndex];
}

echo "Sum: " . array_sum($middles) . "\n";
