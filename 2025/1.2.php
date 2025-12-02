<?php

require_once __DIR__ . '/../common.php';

$startingPoint = 50;
$currentPoint = $startingPoint;

$numZero = 0;

foreach ($reader->lines() as $line) {
    preg_match('/(?P<direction>[L|R])(?P<distance>[0-9]+)/', $line, $matches);
    $direction = $matches['direction'];
    $distance = (int) $matches['distance'];
    $currentPoint = move($currentPoint, $distance, $direction, $numZero);
}

function move(int $startingPoint, int $distance, string $direction, int &$numZero): int
{
    $lowerLimit = 0;
    $upperLimit = 99;
    $currentPoint = $startingPoint;
    $modifier = $direction === 'R' ? 1 : -1;

    while ($distance > 0) {
        $currentPoint += $modifier;
        if ($currentPoint > $upperLimit) {
            $currentPoint = $lowerLimit;
        } elseif ($currentPoint < $lowerLimit) {
            $currentPoint = $upperLimit;
        }

        if ($currentPoint === 0) {
            $numZero++;
        }

        $distance--;
    }

    return $currentPoint;
}

echo "Total number of times dial ticks zero = $numZero\n";