<?php

$fish = [];
$totalDays = 80;

class Lanternfish
{
    public function __construct(private int $timer = 8)
    {
    }

    public function advanceDay(): void
    {
        if ($this->timer === 0) {
            $this->timer = 6;
            return;
        }

        $this->timer--;
    }

    public function canSpawn(): bool
    {
        return $this->timer === 0;
    }
}

foreach (explode(',', file_get_contents(__DIR__ . '/input/6.txt')) as $age) {
    $fish[] = new Lanternfish((int) trim($age));
}

while ($totalDays > 0) {
    $startingCount = count($fish);
    for ($i = 0; $i < $startingCount; $i++) {
        $lanternFish = $fish[$i];
        if ($lanternFish->canSpawn()) {
            $fish[] = new Lanternfish();
        }

        $lanternFish->advanceDay();
    }
    $totalDays--;
}

$totalFish = count($fish);


echo "How many lanternfish would there be after 80 days? $totalFish\n";
echo "Done\n";
