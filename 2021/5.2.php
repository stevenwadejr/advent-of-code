<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/5.txt');

$points = [];

$reader->loop(function ($line) use (&$points) {
    $lines = explode(' -> ', $line);
    [$x1, $y1] = explode(',', $lines[0]);
    [$x2, $y2] = explode(',', $lines[1]);
    $x1 = (int) $x1;
    $y1 = (int) $y1;
    $x2 = (int) $x2;
    $y2 = (int) $y2;

    if ($x1 === $x2 && $y1 != $y2) {
        foreach (range($y1, $y2) as $i) {
            $key = $x1 . ',' . $i;
            $points[$key] = isset($points[$key]) ? $points[$key] + 1 : 1;
        }
    } elseif ($y1 === $y2 && $x1 != $x2) {
        foreach (range($x1, $x2) as $i) {
            $key = $i . ',' . $y1;
            $points[$key] = isset($points[$key]) ? $points[$key] + 1 : 1;
        }
    } elseif ($x1 !== $x2 && $y1 !== $y2) {
        $xRange = range($x1, $x2);
        $yRange = range($y1, $y2);

        // Counts must match to be diagonal
        if (count($xRange) === count($yRange)) {
            foreach ($xRange as $i => $x) {
                $key = $x . ',' . $yRange[$i];
                $points[$key] = isset($points[$key]) ? $points[$key] + 1 : 1;
            }
        }
    }
});

$pointsWithAtLeastTwo = count(
    array_filter(
        $points,
        function ($point) {
            return $point >= 2;
        }
    )
);


echo "At how many points do at least two lines overlap? $pointsWithAtLeastTwo\n";
echo "Done\n";
