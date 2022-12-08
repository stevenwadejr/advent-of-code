<?php

$packet = file_get_contents(__DIR__ . '/input/6.txt');

$pointer = 0;
$buffer = [];

foreach (str_split($packet) as $char) {
    ++$pointer;
    $buffer[] = $char;
    $tmp = array_unique($buffer);
    if (count($tmp) === 14) {
        die("Characters processed before start of packet = $pointer\n");
    }

    if (count($buffer) === 14) {
        // Take the buffer back down to 13 and we'll add the 14th on the next iteration.
        // Always only keep 14 chars in the buffer.
        array_shift($buffer);
    }
}