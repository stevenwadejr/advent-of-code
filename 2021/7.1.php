<?php

$crabPositions = explode(',', file_get_contents(__DIR__ . '/input/7.txt'));
$uniquePositions = array_values(array_unique($crabPositions));

$min = min($uniquePositions);
$max = max($uniquePositions);
$halfway = floor(($max - $min) / 2);

$fuelCost = ($max - $min) / (count($crabPositions) - 1);

// $fuelCost = 0;

// foreach ($crabPositions as $pos) {
//     $fuelCost += abs($pos - $halfway);
// }


echo "How much fuel must they spend to align to that position? $fuelCost\n";
echo "Done\n";
