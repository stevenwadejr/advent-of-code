<?php

require_once __DIR__ . '/../common.php';

if ($isExample) {
    $text = "xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))";
} else {
    $lines = iterator_to_array($reader->lines());
    $text = implode("\n", $lines);
}

$sum = 0;

preg_match_all("/do\(\)|don't\(\)|mul\(([0-9]{1,3}),([0-9]{1,3})\)/mi", $text, $matches, PREG_SET_ORDER);

$do = true;
foreach ($matches as $match) {
    $instruction = preg_replace('/[^a-zA-Z]+/', '', $match[0]);
    if ($instruction === 'mul' && $do) {
        $sum += (int) $match[1] * (int) $match[2];
        continue;
    }

    $do = $instruction === 'do';
}

echo "Sum: $sum\n";
