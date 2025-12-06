<?php

require_once __DIR__ . '/../common.php';

$ranges = [];

$numberOfFreshIngredients = 0;

$isProcessingIds = true;
foreach ($reader->lines() as $line) {
    if (trim($line) === '') {
        $isProcessingIds = false;
        continue;
    }

    if ($isProcessingIds) {
        [$low, $high] = explode('-', $line);
        $ranges[] = [(int) $low, (int) $high];
        continue;
    }

    if (isIngredientFresh((int) trim($line))) {
        $numberOfFreshIngredients++;
    }
}

function isIngredientFresh(int $id): bool
{
    global $ranges;

    foreach ($ranges as [$low, $high]) {
        if ($id >= $low && $id <= $high) {
            return true;
        }
    }

    return false;
}

echo "Number of fresh ingredients = $numberOfFreshIngredients\n";