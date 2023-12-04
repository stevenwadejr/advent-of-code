<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/1.txt');

$sum = 0;
$reader->loop(function (string $line) use (&$sum) {
    $str = preg_replace('/[^0-9]+/', '', $line);
    $numbers = str_split($str);
    $num = (int) (reset($numbers) . end($numbers));
    $sum += $num;
});

echo "Sum = $sum\n";
