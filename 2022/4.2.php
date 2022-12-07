<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/4.txt');

$containedPairs = 0;

$reader->loop(function ($line) use (&$containedPairs) {
    [$elf1, $elf2] = explode(',', $line);
    $elf1Assignments = explode('-', $elf1);
    $elf2Assignments = explode('-', $elf2);
    $elf1Range = range($elf1Assignments[0], $elf1Assignments[1]);
    $elf2Range = range($elf2Assignments[0], $elf2Assignments[1]);

    $intersect = array_intersect($elf1Range, $elf2Range);
    if ($intersect) {
        $containedPairs++;
    }

});

echo "Contained Pairs = $containedPairs\n";