<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

function calculateGamePowers(array $sets): int
{
    $red = 0;
    $green = 0;
    $blue = 0;

    foreach ($sets as $set) {
        preg_match_all('/([0-9]+) (blue|red|green)/', $set, $matches, PREG_SET_ORDER);
        foreach ($matches as [, $num, $color]) {
            $num = (int) $num;
            if ($num > $$color) {
                $$color = $num;
            }
        }
    }

    return $red * $green * $blue;
}

foreach ($reader->lines() as $line) {
    $sets = array_map('trim', explode(';', $line));
    $sum += calculateGamePowers($sets);
};

echo "Sum = $sum\n";
