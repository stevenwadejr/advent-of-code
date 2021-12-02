<?php

require_once __DIR__ . '/InputReader.php';

$last = null;
$numIncreased = 0;

$reader = new InputReader(__DIR__ . '/input/1.1.txt');

$reader->loop(function ($line) use (&$last, &$numIncreased) {
    $input = (int) trim($line);
    if ($last !== null && $input > $last) {
        $numIncreased++;
    }

    $last = $input;
});

echo "How many measurements are larger than the previous measurement? $numIncreased\n";

echo "done";
