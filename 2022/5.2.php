<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/5.txt');

$stacks = [];

$initStacking = function (string $line) use (&$stacks) {
    static $tmp = [];

    if (preg_match('/\[\D\]/', $line)) {
        $stackIdx = 1;
        for ($i = 1; $i < strlen($line); $i += 4) {
            $char = trim($line[$i]);
            if (!empty($char)) {
                $tmp[$stackIdx][] = $char;
            }
            $stackIdx++;
        }
    } else {
        foreach ($tmp as $idx => $stack) {
            $stacks[$idx] = array_reverse($stack);
        }
    }
};

$move = function ($count, $from, $to) use (&$stacks) {
    $tmp = [];
    for ($i = 0; $i < $count; $i++) {
        $item = array_pop($stacks[$from]);
        array_unshift($tmp, $item);
    }

    $stacks[$to] = array_merge($stacks[$to], $tmp);
};

$reader->loop(function ($line) use (&$initStacking, &$move) {
    if (preg_match('/\[\D\]/', $line) || $line === "\n") {
        $initStacking($line);
        return;
    } elseif (preg_match('/move ([0-9]+) from ([0-9]+) to ([0-9]+)/', $line, $instructions)) {
        $move($instructions[1], $instructions[2], $instructions[3]);
    }

}, false);

ksort($stacks);

foreach ($stacks as $stack) {
    echo array_pop($stack);
}

echo "\nDone!\n";