<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/3.txt');

$lineTotal = 0;
$bits = [];
$oxygenGeneratorRating = '';
$co2ScrubberRating = '';

$reader->loop(function ($line) use (&$bits, &$lineTotal) {
    $bits[] = (string) $line;
    $lineTotal++;
});

function findRating(array $bits, string $decider = 'more')
{
    $idx = 0;

    while (count($bits) > 1) {

        $ones = [];
        $zeroes = [];

        foreach ($bits as $bit) {
            if ($bit[$idx] === '1') {
                $ones[] = $bit;
            } else {
                $zeroes[] = $bit;
            }
        }

        $onesCount = count($ones);
        $zeroesCount = count($zeroes);

        if ($decider === 'more') {
            if ($onesCount > $zeroesCount) {
                $bits = array_diff($bits, $zeroes);
            } elseif ($onesCount === $zeroesCount) {
                $toRemove = $ones[0][$idx] === '1' ? $zeroes : $ones;
                $bits = array_diff($bits, $toRemove);
            } else {
                $bits = array_diff($bits, $ones);
            }
        } else {
            if ($zeroesCount < $onesCount) {
                $bits = array_diff($bits, $ones);
            } elseif ($onesCount === $zeroesCount) {
                $toRemove = $zeroes[0][$idx] === '0' ? $ones : $zeroes;
                $bits = array_diff($bits, $toRemove);
            } else {
                $bits = array_diff($bits, $zeroes);
            }
        }

        $idx++;
    }

    return bindec(array_shift($bits));
}

$oxygenGeneratorRating = findRating($bits, 'more');
$co2ScrubberRating = findRating($bits, 'less');

$lifeSupportRating = $oxygenGeneratorRating * $co2ScrubberRating;

echo "What is the life support rating of the submarine? $lifeSupportRating\n";

echo "done";
