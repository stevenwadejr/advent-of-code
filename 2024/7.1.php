<?php

require_once __DIR__ . '/../common.php';

function canGetAnswer(array $problem, int $answer): bool
{
    $dfs = function (int $currentValue, array $used) use (&$answer, &$problem, &$dfs): bool {
        if ($currentValue === $answer) {
            return true;
        }

        for ($i = 0; $i < count($problem); $i++) {
            if (false === ($used[$i] ?? false)) {
                $used[$i] = true;

                if ($dfs($currentValue + $problem[$i], $used)) {
                    return true;
                }

                // Avoid multiplying by 0
                if (
                    $currentValue !== 0
                    && $dfs($currentValue * $problem[$i], $used)
                ) {
                    return true;
                }

                $used[$i] = false;
            }
        }

        return false;
    };

    for ($i = 0; $i < count($problem); $i++) {
        $used = array_fill(0, count($problem), false);
        $used[$i] = true;

        if ($dfs($problem[$i], $used)) {
            return true;
        }
    }

    return false;
}

function find_combination(array $numbers, int $target)
{
    # Base case: if the list has only one number, check if it equals the target
    if (count($numbers) == 1) {
        return $numbers[0] == $target;
    }

    # Try every possible pair of operations (addition or multiplication)
    for ($i = 0; $i < count($numbers); $i++) {
        $left = array_slice($numbers, 0, $i);
        $right = array_slice($numbers, $i);

        $result_add = array_sum($left) + array_sum($right);
        if ($result_add == $target) {
            return true;
        }

        $result_mul = 1;
        foreach ($left as $num) {
            $result_mul *= $num;
        }
        foreach ($right as $num) {
            $result_mul *= $num;
        }

        if ($result_mul == $target) {
            return true;
        }
    }

    return false;
}

$sum = 0;

foreach ($reader->lines() as $line) {
    [$answer, $problemSet] = explode(': ', $line);
    $problem = array_map('intval', explode(' ', $problemSet));
    $answer = intval($answer);

    echo "Checking answer: $answer\n";
    if (find_combination($problem, $answer)) {
        $sum += $answer;
    }
}

echo "Sum: $sum\n";
