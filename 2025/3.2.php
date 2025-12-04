<?php

require_once __DIR__ . '/../common.php';

$totalJoltage = 0;

foreach ($reader->lines() as $line) {
    $joltage = findJoltage(array_map('intval', str_split($line)));
    // echo "joltage = $joltage\n";

    $totalJoltage += $joltage;
}

function findJoltage(array $bank): int
{
    $finalSize = 12;
    $dropsAllowed = count($bank) - $finalSize;
    $dropsPerformed = 0;
    foreach ($bank as $index => $battery) {
        $next = $bank[$index + 1] ?? 0;
        if ($next > $battery && $dropsPerformed < $dropsAllowed) {
            unset($bank[$index]);
            $dropsPerformed++;
        }
    }

    $lowest = 1;
    while (count($bank) > 12) {
        foreach ($bank as $index => $battery) {
            if ($battery === $lowest && $dropsPerformed < $dropsAllowed) {
                unset($bank[$index]);
                $dropsPerformed++;
            }
        }
        $lowest++;
    }

    return (int)  implode('', $bank);
}

echo "Total output joltage = $totalJoltage\n";