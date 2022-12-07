<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/3.txt');

$total = 0;
$alphabet = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
$alphabet = array_merge($alphabet, array_map('strtoupper', $alphabet));
$group = [];
$pointer = 1;

$reader->loop(function ($line) use (&$total, &$alphabet, &$group, &$pointer) {
    $group[] = str_split($line);

    if ($pointer === 3) {
        $intersect = array_intersect(...$group);
        $shared = array_shift($intersect);
        $idx = array_search($shared, $alphabet);
        $priority = $idx + 1;
        $total += $priority;  

        $group = [];
        $pointer = 1;
        return;
    }

    $pointer++; 
});

echo "Priority Sum = $total\n";