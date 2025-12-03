<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

foreach ($reader->csv() as $range) {
    [$lower, $upper] = explode('-', $range);
    foreach (range((int) $lower, (int) $upper) as $id) {
        $sum += isInvalidId($id) ? $id : 0;
    }
}

function isInvalidId(int $id): bool
{
    $pattern = '~
        \A    # start of the string
        # find the largest pattern first in a lookahead
        # (the idea is to compare the size of trailing digits with the smallest pattern)
        (?= (\d+) \1+ (\d*) \z )
        # find the smallest pattern
        (?<pattern> \d+? ) \3+
        # that has the same or less trailing digits
        (?! .+ \2 \z)
        # capture the eventual trailing digits
        (?= (?<trailing> \d* ) )
    ~x';

    if (preg_match($pattern, $id, $m) && $m['trailing'] === '') {
        return true;
    }

    return false;
}

echo "Sum of invalid IDs = $sum\n";