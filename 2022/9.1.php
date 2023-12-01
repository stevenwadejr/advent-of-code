<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/9.txt');

$x = 0;
$y = 0;
$tailVisits = ["$x,$y"];
$tailPos = [$x, $y];
$headPos = [$x, $y];
$lastDir = null;
$reader->loop(function ($line) use (&$tailVisits, &$x, &$y, &$lastDir, &$tailPos, &$headPos) {
    [$direction, $units] = explode(' ', $line);
    $visits = [];
    switch ($direction) {
        case 'R':
            // if ($lastDir === 'U' && $units > 1) {
            //     $y++;
            // } elseif ($lastDir === 'D' && $units > 1) {
            //     $y--;
            // }
            // for ($i = 1; $i < $units; $i++) {
            //     $x++;
            //     $visits[] = "$x,$y";
            // }
            $x += $units;
            break;
        case 'L':
            // if ($lastDir === 'U' && $units > 1) {
            //     $y++;
            // } elseif ($lastDir === 'D' && $units > 1) {
            //     $y--;
            // }
            // for ($i = 1; $i < $units; $i++) {
            //     $x--;
            //     $visits[] = "$x,$y";
            // }

            $x -= $units;
            break;
        case 'U':
            // if ($lastDir === 'R' && $units > 1) {
            //     $x++;
            // } elseif ($lastDir === 'L' && $units > 1) {
            //     $x--;
            // }
            // for ($i = 1; $i < $units; $i++) {
            //     $y++;
            //     $visits[] = "$x,$y";
            // }
            // $y++;
            $y += $units;
            break;
        case 'D':
            // if ($lastDir === 'R' && $units > 1) {
            //     $x++;
            // } elseif ($lastDir === 'L' && $units > 1) {
            //     $x--;
            // }
            // for ($i = 1; $i < $units; $i++) {
            //     $y--;
            //     $visits[] = "$x,$y";
            // }
            // $y--;
            $y -= $units;
            break;
    }

    $headPos = [$x, $y];
    $hasDiff = true;
    $i = 0;
    while ($hasDiff) {
        $diff = [
            $headPos[0] - $tailPos[0],
            $headPos[1] - $tailPos[1]
        ];

        // echo "Tail Pos: " . implode(',', $tailPos) . "\n";
        // echo "Diff: " . implode(',', $diff) . "\n";

        if ((abs($diff[0]) == 1 && $diff[1] == 0)
            || ($diff[0] == 0 && abs($diff[1]) == 1)
            || ($diff[0] == 0 && $diff[1] == 0)
            || (abs($diff[0]) === 1 && abs($diff[1]) === 1)
        ) {
            $hasDiff = false;
            break;
        }

        if ($diff[0] != 0) {
            $tailPos[0] += $diff[0] > 0 ? 1 : -1;
        }

        if ($diff[1] != 0) {
            $tailPos[1] += $diff[1] > 0 ? 1 : -1;
        }

        $visits[] = implode(',', $tailPos);

        $i++;
    };

    // echo "\n\n";

    $tailVisits = array_merge($tailVisits, $visits);
});

$numVisits = count(array_unique($tailVisits));

// assert($numVisits === 13, "The number of visits ($numVisits) does not match the expected 13");

echo "Number of visits: $numVisits\n";
