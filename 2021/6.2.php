<?php

$totalDays = 256;
$fish = [];

foreach (explode(',', file_get_contents(__DIR__ . '/input/6.txt')) as $age) {
    $age = (int) trim($age);
    $fish[$age] = ($fish[$age] ?? 0) + 1;
}

while ($totalDays > 0) {
    $temp = [];
    $readyToSpawn = $fish[0] ?? 0;
    $newFish = $readyToSpawn;


    for ($i = 8; $i >= 0; $i--) {
        if ($i === 0) {
            $temp[8] = $newFish;
            $temp[6] = ($temp[6] ?? 0) + $readyToSpawn;
        } else {
            $temp[$i - 1] = $fish[$i] ?? 0;
        }
    }

    $fish = $temp;

    $totalDays--;
}

$totalFish = array_sum($fish);


echo "How many lanternfish would there be after 256 days? $totalFish\n";
echo "Done\n";
