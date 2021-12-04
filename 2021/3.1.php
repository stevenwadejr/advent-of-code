<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/3.txt');

$lineTotal = 0;
$bits = [];
$gammaRate = '';
$epsilonRate = '';

$reader->loop(function ($line) use (&$bits, &$lineTotal) {
    for ($i = 0; $i < strlen($line); $i++) {
        $bits[$i] = ($bits[$i] ?? 0) + ((int) $line[$i]);
    }

    $lineTotal++;
});

foreach ($bits as $bit) {
    $gammaRate .= ($lineTotal / $bit) < 2 ? '1' : '0';
    $epsilonRate .= ($lineTotal / $bit) < 2 ? '0' : '1';
}

$gammaRate = bindec($gammaRate);
$epsilonRate = bindec($epsilonRate);

$powerConsumption = ((int) $gammaRate) * ((int) $epsilonRate);

echo "What is the power consumption of the submarine? $powerConsumption\n";

echo "done";
