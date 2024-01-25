<?php

require_once __DIR__ . '/../common.php';

$sum = 0;

$rows = iterator_to_array($reader->lines());
$rowCount = count($rows);
$rowLength = strlen($rows[0]);

function isSymbol(mixed $char): bool
{
    return !is_null($char) && !is_numeric($char) && $char !== '.';
}

foreach ($rows as $rowNum => $row) {
    preg_match_all('/[0-9]+/', $row, $matches, PREG_OFFSET_CAPTURE);
    if (!empty($matches[0])) {
        foreach ($matches[0] as [$number, $index]) {
            $aheadPos = $index + strlen($number);
            $startIdx = $index > 0 ? $index - 1 : 0;
            $endIdx = $aheadPos < $rowLength ? $aheadPos + 1 : $rowLength;
            // Look up
            if ($rowNum > 0) {
                // Check diagonals
                $aboveStr = substr($rows[$rowNum - 1], $startIdx, $aheadPos);
                $aboveStr = preg_replace('/[0-9\.]+/', '', $aboveStr);
                if (strlen($aboveStr) > 0) {
                    $sum += (int) $number;
                    continue;
                }
            }

            // Look down
            if ($rowNum < ($rowCount - 1)) {
                // Check diagonals
                $belowStr = substr($rows[$rowNum + 1], $startIdx, $endIdx);
                $belowStr = preg_replace('/[0-9\.]+/', '', $belowStr);
                if (strlen($belowStr) > 0) {
                    $sum += (int) $number;
                    continue;
                }
            }

            // Look ahead
            if ($aheadPos < $rowLength && isSymbol($row[$aheadPos])) {
                $sum += (int) $number;
                continue;
            }

            // Look back
            if ($index > 0 && isSymbol($row[$index - 1])) {
                $sum += (int) $number;
                continue;
            }
        }
    }
};

echo "Sum = $sum\n";
