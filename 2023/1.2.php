<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/1.txt');

$numMap = [
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9,
];

$sum = 0;
$reader->loop(function (string $line) use (&$sum, $numMap) {
    $numbers = [];
    preg_match_all('/[0-9]/', $line, $matches, PREG_OFFSET_CAPTURE);

    foreach ($matches[0] ?? [] as [$number, $index]) {
        $numbers[$index] = $number;
    }

    foreach ($numMap as $search => $replace) {
        $lastPos = 0;
        while (($lastPos = strpos($line, $search, $lastPos)) !== false) {
            $numbers[$lastPos] = $replace;
            $lastPos = $lastPos + strlen($search);
        }
    }
    ksort($numbers);
    $numbers = array_values($numbers);

    $num = (int) (reset($numbers) . end($numbers));
    $sum += $num;
});

echo "Sum = $sum\n";