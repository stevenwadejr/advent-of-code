<?php

$handle = fopen(__DIR__ . '/input/1.1.txt', "r");
$last = null;
$numIncreased = 0;

while (($line = fgets($handle)) !== false) {
    $input = (int) trim($line);
    if ($last !== null && $input > $last) {
        $numIncreased++;
    }

    $last = $input;
}

fclose($handle);

echo "How many measurements are larger than the previous measurement? $numIncreased\n";

echo "done";
