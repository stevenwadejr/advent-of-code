<?php

require_once __DIR__ . '/../common.php';

$rawRanges = [];

$numberOfFreshIngredients = 0;

$isProcessingIds = true;
foreach ($reader->lines() as $line) {
    if (trim($line) === '') {
        break;
    }
    
    $addRange = false;
    [$low, $high] = array_map('intval', explode('-', $line));
    $rawRanges[] = [$low, $high];
}

usort(
    $rawRanges,
    fn ($a, $b) => $a[0] <=> $b[0]
);

$ranges = [];

$current = $rawRanges[0];
foreach ($rawRanges as $key => [$min, $max]) {
    if ($current[0] === $min && $current[1] === $max) {
        continue;
    }

    $currentMax = $current[1];

    // They don't overlap
    if ($min > ($currentMax + 1)) {
        $ranges[] = $current;
        $current = [$min, $max];
        continue;
    }

    $current[1] = max($max, $currentMax);
}

$ranges[] = $current;

foreach ($ranges as [$low, $high]) {
    $numberOfFreshIngredients += $high - $low + 1;
}

// print_r($ranges);

echo "Number of fresh ingredients = $numberOfFreshIngredients\n";