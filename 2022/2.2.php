<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/2.txt');

enum Choice
{
    case ROCK;
    case PAPER;
    case SCISSORS;

    public static function elf(string $choice): Choice
    {
        return match ($choice) {
            'A' => Choice::ROCK,
            'B' => Choice::PAPER,
            'C' => Choice::SCISSORS,
        };
    }

    public static function me(string $myChoice, Choice $elf): Choice
    {
        $lose = function () use ($elf) {
            return match (true) {
                $elf === Choice::ROCK => Choice::SCISSORS,
                $elf === Choice::SCISSORS => Choice::PAPER,
                $elf === Choice::PAPER => Choice::ROCK,
            };
        };

        $win = function () use ($elf) {
            return match (true) {
                $elf === Choice::ROCK => Choice::PAPER,
                $elf === Choice::SCISSORS => Choice::ROCK,
                $elf === Choice::PAPER => Choice::SCISSORS,
            };
        };

        return match ($myChoice) {
            'X' => $lose($elf),
            'Y' => $elf,
            'Z' => $win($elf),
        };
    }

    public function choiceScore(): int
    {
        return match ($this) {
            Choice::ROCK => 1,
            Choice::PAPER => 2,
            Choice::SCISSORS => 3,
        };
    }

    public function compare(Choice $choice): Outcome
    {
        return match (true) {
            $this === $choice => Outcome::DRAW,
            $this === Choice::PAPER && $choice === Choice::ROCK => Outcome::WIN,
            $this === Choice::ROCK && $choice === Choice::SCISSORS => Outcome::WIN,
            $this === Choice::SCISSORS && $choice === Choice::PAPER => Outcome::WIN,
            default => Outcome::LOSE,
        };
    }
}

enum Outcome
{
    case WIN;
    case LOSE;
    case DRAW;

    public function score(): int
    {
        return match ($this) {
            Outcome::WIN => 6,
            Outcome::LOSE => 0,
            Outcome::DRAW => 3,
        };
    }
}

$totalScore = 0;

$reader->loop(function ($line) use (&$totalScore) {
    $choices = explode(' ', $line);
    $elf = Choice::elf($choices[0]);
    $me = Choice::me($choices[1], $elf);
    $outcome = $me->compare($elf);

    $totalScore += $me->choiceScore() + $outcome->score();
});

echo "Total score: $totalScore\n";
