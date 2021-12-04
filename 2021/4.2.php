<?php

require_once __DIR__ . '/InputReader.php';

$reader = new InputReader(__DIR__ . '/input/4.txt');

class BingoBoard
{
    private $rows = [];
    private $matches = [];
    private $winningDraw = 0;

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

    public function setWinningDraw(int $draw): void
    {
        $this->winningDraw = $draw;
    }

    public function getSum(): int
    {
        $sum = 0;
        foreach ($this->matches as $rowIdx => $row) {
            foreach ($row as $colIdx => $num) {
                if ($num === 0) {
                    $sum += $this->rows[$rowIdx][$colIdx];
                }
            }
        }

        return $sum * $this->winningDraw;
    }
}

$numberPool = [];
$boards = [];
$currentLine = 0;
$currentBoard = null;
$finalScore = 0;
$lastWinningBoard = null;

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
        // Skip already solved boards
        if ($board->isSolved()) {
            continue;
        }

        $board->checkForMatch($draw);
        if ($board->isSolved()) {
            $board->setWinningDraw($draw);
            $lastWinningBoard = $board;
        }
    }
}

$finalScore = $lastWinningBoard->getSum();

echo "Once it wins, what would its final score be? $finalScore\n";
echo "Done\n";
