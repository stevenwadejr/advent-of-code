
<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/2.1.txt');

class Position
{
    private $horizontal = 0;
    private $depth = 0;
    private $aim = 0;

    public function forward(int $distance)
    {
        $this->horizontal += $distance;
        $this->depth += ($this->aim * $distance);
    }

    public function up(int $distance)
    {
        $this->aim -= $distance;
    }

    public function down(int $distance)
    {
        $this->aim += $distance;
    }

    public function getTotal()
    {
        return $this->horizontal * $this->depth;
    }
}

$position = new Position();

$reader->loop(function ($line) use (&$position) {
    [$direction, $distance] = explode(' ', $line);
    if (!empty($direction) && !empty($distance) && method_exists($position, $direction)) {
        $position->$direction($distance);
    }
});

$total = $position->getTotal();

echo "What do you get if you multiply your final horizontal position by your final depth? $total\n";

echo "done";
