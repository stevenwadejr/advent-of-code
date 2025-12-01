<?php

require_once __DIR__ . '/../common.php';

$numSafe = 0;

function isSafe(array $levels): bool
{
    $current = 0;
    $previous = null;
    $desc = $levels;
    $asc = $levels;

    asort($asc);
    arsort($desc);
    if ($asc !== $levels && $desc !== $levels) {
        return false;
    }

    foreach ($levels as $level) {
        if ($previous === null) {
            $previous = $level;
            continue;
        }

        $current = $level;
        $diff = abs($current - $previous);
        if ($diff > 3 || $diff === 0) {
            return false;
        }
        $previous = $current;
    }

    return true;
}

function isSafeRecurrsive(array $levels): bool
{
    if (isSafe($levels)) {
        return true;
    }

    foreach (array_keys($levels) as $i) {
        $newLevels = $levels;
        unset($newLevels[$i]);
        // print_r($newLevels);
        if (isSafe(array_values($newLevels))) {
            return true;
        }
    }

    return false;
}

foreach ($reader->lines() as $line) {
    $levels = array_map('intval', explode(' ', $line));

    if (isSafeRecurrsive($levels)) {
        $numSafe++;
    }
}

echo "Num safe: $numSafe\n";
