<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/1.txt');

$elfFoods = [];
$elfCalories = [];

$index = 0;
$reader->loop(function ($line) use (&$index, &$elfFoods, &$elfCalories) {
    if (empty($line)) {
        $index++;
        return;
    }

    $elfFoods[$index][] = $line;
    $caloriesTotal = $elfCalories[$index] ?? 0;
    $caloriesTotal += (int) $line;
    $elfCalories[$index] = $caloriesTotal;
});

arsort($elfCalories);

echo 'Most calories carried: ' . array_shift($elfCalories) . "\n";
