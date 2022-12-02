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

$total = 0;
for ($i = 1; $i <= 3; $i++) {
    $total += (int) array_shift($elfCalories);
}

echo "Top 3 total: $total\n";
