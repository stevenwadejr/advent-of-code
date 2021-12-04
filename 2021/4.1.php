<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/4.txt');

class BingoBoard
{
    private $rows = [];
    private $matches = [];

    public function addRow(array $row): void
    {
        $this->rows[] = $row;
        $this->matches[] = array_pad([], count($row), 0);
    }

    public function checkForMatch(int $draw): void
    {
        foreach ($this->rows as $rowIdx => $row) {
            foreach ($row as $columnIdx => $num) {
                if ($num === $draw) {
                    $this->matches[$rowIdx][$columnIdx] = 1;
                }
            }
        }
    }

    public function isSolved(): bool
    {
        $colCount = count($this->matches[0]);

        // Check for matches in the row first
        foreach ($this->matches as $row) {
            if (array_sum($row) === $colCount) {
                return true;
            }
        }

        // Now check for column matches
        for ($col = 0; $col < $colCount; $col++) {
            $colVals = [];
            foreach ($this->matches as $row) {
                $colVals[] = $row[$col];
            }

            if (array_sum($colVals) === $colCount) {
                return true;
            }
        }

        return false;
    }

    public function getSum(int $draw): int
    {
        $sum = 0;
        foreach ($this->matches as $rowIdx => $row) {
            foreach ($row as $colIdx => $num) {
                if ($num === 0) {
                    $sum += $this->rows[$rowIdx][$colIdx];
                }
            }
        }

        return $sum * $draw;
    }
}

$numberPool = [];
$boards = [];
$currentLine = 0;
$currentBoard = null;
$finalScore = 0;

$reader->loop(function ($line) use (&$numberPool, &$boards, &$currentLine, &$currentBoard) {
    if (++$currentLine === 1) {
        $numberPool = array_map('intval', explode(',', $line));
    } elseif (empty(trim($line))) {
        $currentBoard = new BingoBoard();
        $boards[] = $currentBoard;
    } elseif ($currentBoard) {
        $row = array_map('intval', explode(' ', preg_replace('/\s+/', ' ', $line)));
        $currentBoard->addRow($row);
    }
});

foreach ($numberPool as $draw) {
    foreach ($boards as $board) {
        $board->checkForMatch($draw);
        if ($board->isSolved()) {
            $finalScore = $board->getSum($draw);
            break 2;
        }
    }
}

echo "What will your final score be if you choose that board? $finalScore\n";
echo "Done\n";
