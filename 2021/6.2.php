<?php
ini_set('memory_limit', '16384M');

require_once __DIR__ . '/LinkedList.php';

$totalFish = 0;

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
    $totalDays = 256;
    $list = new LinkedList();
    $list->append(new Lanternfish((int) trim($age)));

    while ($totalDays > 0) {

        foreach ($list->each() as $node) {
            $lanternFish = $node->data;
            if ($lanternFish->canSpawn()) {
                $list->append(new Lanternfish());
            }

            $lanternFish->advanceDay();
        }

        $totalDays--;
    }

    $totalFish += $list->size();
    unset($list);
}


echo "How many lanternfish would there be after 256 days? $totalFish\n";
echo "Done\n";
