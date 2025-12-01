<?php

require_once __DIR__ . '/../common.php';

$lines = iterator_to_array($reader->lines());
$text = implode("\n", $lines);

$sum = 0;

preg_match_all('/mul\(([0-9]{1,3}),([0-9]{1,3})\)/mi', $text, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $sum += (int) $match[1] * (int) $match[2];
}

echo "Sum: $sum\n";
