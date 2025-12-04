<?php

require_once __DIR__ . '/../common.php';

$totalJoltage = 0;

foreach ($reader->lines() as $line) {
    $joltage = findJoltage(array_map('intval', str_split($line)));
    // echo "joltage = $joltage\n";

    $totalJoltage += $joltage;
}

// Final solution reached via ChatGPT because I spent many hours and attempts
// on this and instead of wracking my brain forever and giving up, I'd like to
// learn something new, so let AI teach me.
function findJoltage(array $bank): int
{
    $finalSize = 12;
    $n = count($bank);
    $dropsAllowed = $n - $finalSize;
    $dropsLeft = $dropsAllowed;

    $result = [];

    foreach ($bank as $digit) {
        // While the last chosen digit is smaller than the current one,
        // and we still have drops left, drop from the end of the result.
        while (!empty($result)
            && end($result) < $digit
            && $dropsLeft > 0
        ) {
            array_pop($result);
            $dropsLeft--;
        }

        $result[] = $digit;
    }

    // If we still have drops left (e.g. digits were non-increasing),
    // drop from the end.
    while ($dropsLeft > 0) {
        array_pop($result);
        $dropsLeft--;
    }

    // Now we might have more than 12 digits (if input was longer).
    // Take only the first 12.
    $result = array_slice($result, 0, $finalSize);

    return (int) implode('', $result);
}

// $totalJoltage = number_format($totalJoltage, thousands_separator: '');

echo "Total output joltage = $totalJoltage\n";