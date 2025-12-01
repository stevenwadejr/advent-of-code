<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

$pairs = [
    'left' => [],
    'right' => [],
];

foreach ($reader->lines() as $line) {
    preg_match('/(\d+)\s+(\d+)/', $line, $matches);
    $pairs['left'][] = (int) $matches[1];
    $pairs['right'][] = (int) $matches[2];
}

sort($pairs['left']);
sort($pairs['right']);

foreach ($pairs['left'] as $i => $left) {
    $distance = abs($left - $pairs['right'][$i]);
    $sum += $distance;
}

echo "Sum: $sum\n";
