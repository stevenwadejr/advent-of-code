<?php

require_once __DIR__ . '/../common.php';

$totalJoltage = 0;

foreach ($reader->lines() as $line) {
    $joltage = findJoltage(array_map('intval', str_split($line)));

    $totalJoltage += $joltage;
}

function findJoltage(array $bank): int
{
    $largestSeen = 0;
    $secondLargestSeen = 0;
    foreach ($bank as $index => $battery) {
        $isLastBattery = $index === array_key_last($bank);
        
        if ($battery > $largestSeen && !$isLastBattery) {
            $largestSeen = $battery;
            $secondLargestSeen = 0;
            continue;
        }

        if ($battery > $secondLargestSeen) {
            $secondLargestSeen = $battery;
            continue;
        }
    }

    return (int) ($largestSeen . $secondLargestSeen);
}

echo "Total output joltage = $totalJoltage\n";