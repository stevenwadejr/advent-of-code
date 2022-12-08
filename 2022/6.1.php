<?php

$packet = file_get_contents(__DIR__ . '/input/6.txt');

$pointer = 0;
$buffer = [];

foreach (str_split($packet) as $char) {
    ++$pointer;
    $buffer[] = $char;
    $tmp = array_unique($buffer);
    if (count($tmp) === 4) {
        die("Characters processed before start of packet = $pointer\n");
    }

    if (count($buffer) === 4) {
        // Take the buffer back down to 3 and we'll add the 4th on the next iteration.
        // Always only keep 4 chars in the buffer.
        array_shift($buffer);
    }
}