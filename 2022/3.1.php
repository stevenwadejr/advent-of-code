<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/3.txt');

$total = 0;
$alphabet = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
$alphabet = array_merge($alphabet, array_map('strtoupper', $alphabet));

$reader->loop(function ($line) use (&$total, &$alphabet) {
    $midway = strlen($line) / 2;
    $firstHalf = str_split(substr($line, 0, $midway));
    $secondHalf = str_split(substr($line, $midway, strlen($line)));
    $shared = array_values(array_filter($secondHalf, fn($l) => in_array($l, $firstHalf)))[0] ?? null;
    $idx = array_search($shared, $alphabet);
    $priority = $idx + 1;
    $total += $priority;    
});

echo "Priority Sum = $total\n";