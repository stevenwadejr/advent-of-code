<?php

require_once __DIR__ . '/InputReader2.php';
require_once __DIR__ . '/Neighbor.php';
require_once __DIR__ . '/Position.php';
require_once __DIR__ . '/Cell.php';
require_once __DIR__ . '/Grid.php';

$callerScript = array_shift($argv);
$scriptName = basename($callerScript, '.php');
$inputDir = dirname($callerScript) . '/input';
$isExample = in_array('--example', $argv);
$day = (string) ((int) $scriptName);
$inputFile = __DIR__ . '/' . $inputDir . '/' . $day . ($isExample ? '.example' : '') . '.txt';
if (!file_exists($inputFile)) {
    exit('File does not exist "' . $inputFile . '"' . PHP_EOL);
}

$reader = new InputReader2($inputFile);
