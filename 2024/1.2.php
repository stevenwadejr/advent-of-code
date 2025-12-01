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

$similarities = [];

foreach ($pairs['left'] as $i => $left) {
    $similarities[$left] = count(array_filter($pairs['right'], fn($right) => $left === $right));
}

$similarities = array_filter($similarities);

print_r($similarities);

foreach ($pairs['left'] as $left) {
    $sum += ($left * ($similarities[$left] ?? 0));
}

echo "Sum: $sum\n";
