<?php

ini_set('memory_limit', '256M');

require_once __DIR__ . '/../common.php';

$coordinates = [];

foreach ($reader->lines() as $line) {
    $line = trim($line);
    if ($line === '') {
        continue;
    }
    $coordinates[] = array_map('intval', explode(',', $line));
}

function calculateEuclideanDistance(array $p, array $q): float
{
    return sqrt(
        ($p[0] - $q[0]) ** 2 +
        ($p[1] - $q[1]) ** 2 +
        ($p[2] - $q[2]) ** 2
    );
}

function getCoordinateKey(array $coordinate): string
{
    return implode(',', $coordinate);
}

$edges = []; // each: ['d' => float, 'pair' => 'x,y,z|x,y,z']

$n = count($coordinates);

for ($i = 0; $i < $n; $i++) {
    $coord1 = $coordinates[$i];
    $key1   = getCoordinateKey($coord1);

    for ($j = $i + 1; $j < $n; $j++) {
        $coord2 = $coordinates[$j];
        $key2   = getCoordinateKey($coord2);

        $d = calculateEuclideanDistance($coord1, $coord2);

        $edges[] = ['d' => $d, 'pair' => $key1 . '|' . $key2];
    }
}

// Sort all edges by distance ascending
usort(
    $edges,
    fn($a, $b) => $a['d'] <=> $b['d']
);


function findLeader(string $junction, array &$leaders): string
{
    if (!isset($leaders[$junction])) {
        $leaders[$junction] = $junction;
        return $junction;
    }

    if ($leaders[$junction] === $junction) {
        return $junction;
    }

    $leaders[$junction] = findLeader($leaders[$junction], $leaders);
    return $leaders[$junction];
}

$leaders = [];
$size    = [];

// Initialize: every junction we encounter will get size 1 when first seen
// Component count starts as number of unique junctions
$junctionKeys = [];
foreach ($coordinates as $coord) {
    $junctionKeys[] = getCoordinateKey($coord);
}
$junctionKeys = array_unique($junctionKeys);
$componentCount = count($junctionKeys);

$lastMergeEdge = null;

foreach ($edges as $edge) {
    [$junction1, $junction2] = explode('|', $edge['pair']);

    $leftLeader  = findLeader($junction1, $leaders);
    $rightLeader = findLeader($junction2, $leaders);

    if (!isset($size[$leftLeader])) {
        $size[$leftLeader] = 1;
    }
    if (!isset($size[$rightLeader])) {
        $size[$rightLeader] = 1;
    }

    // Already in the same circuit
    if ($leftLeader === $rightLeader) {
        continue;
    }

    // Union-by-size
    if ($size[$leftLeader] < $size[$rightLeader]) {
        [$leftLeader, $rightLeader] = [$rightLeader, $leftLeader];
    }

    // Merge right into left
    $leaders[$rightLeader] = $leftLeader;
    $size[$leftLeader]    += $size[$rightLeader];
    unset($size[$rightLeader]);

    $componentCount--;
    $lastMergeEdge = $edge['pair'];

    if ($componentCount === 1) {
        // Everything is now in one circuit; this is the edge we care about
        break;
    }
}

if ($lastMergeEdge === null) {
    throw new RuntimeException("Did not find a final merge edge");
}

[$a, $b] = explode('|', $lastMergeEdge);

[$ax, $ay, $az] = array_map('intval', explode(',', $a));
[$bx, $by, $bz] = array_map('intval', explode(',', $b));

$answer = $ax * $bx;

echo "Part 2 answer: $answer\n";