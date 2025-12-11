<?php

require_once __DIR__ . '/../common.php';

$coordinates = [];

$distanceCalculations = [];

foreach ($reader->lines() as $line) {
    $coordinates[] = array_map('intval', explode(',', $line));
}

// https://en.wikipedia.org/wiki/Euclidean_distance
function calculateEuclideanDistance(array $p, array $q): int|float
{
    return sqrt(
        pow($p[0] - $q[0], 2) +
        pow($p[1] - $q[1], 2) +
        pow($p[2] - $q[2], 2)
    );
}

function getCoordinateKey(array $coordinate): string
{
    return implode(',', $coordinate);
}

$maxEdges = 1000;
$edges    = []; // each element: ['d' => float, 'pair' => 'x,y,z|x,y,z']

$n = count($coordinates);

for ($i = 0; $i < $n; $i++) {
    $coord1    = $coordinates[$i];
    $key1      = getCoordinateKey($coord1);

    for ($j = $i + 1; $j < $n; $j++) {
        $coord2 = $coordinates[$j];
        $key2   = getCoordinateKey($coord2);

        $d = calculateEuclideanDistance($coord1, $coord2);

        if (count($edges) < $maxEdges) {
            $edges[] = ['d' => $d, 'pair' => $key1 . '|' . $key2];
            continue;
        }

        // We already have 1000 edges; see if this one is better than the current worst
        // Find the index of the current max distance in $edges (1000 items, so O(1000) is fine)
        $maxIndex   = 0;
        $maxDist    = $edges[0]['d'];
        $edgeCount  = count($edges); // should be 1000

        for ($k = 1; $k < $edgeCount; $k++) {
            if ($edges[$k]['d'] > $maxDist) {
                $maxDist  = $edges[$k]['d'];
                $maxIndex = $k;
            }
        }

        // If this new distance is smaller than the current worst, replace it
        if ($d < $maxDist) {
            $edges[$maxIndex] = ['d' => $d, 'pair' => $key1 . '|' . $key2];
        }
        // Otherwise ignore this pair; it's not among the 1000 closest
    }
}

// Now sort the kept edges by distance ascending
usort(
    $edges,
    fn ($a, $b) => $a['d'] <=> $b['d']
);

$leaders = [];
$size    = [];

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

// We already made sure we only have up to 1000 edges,
// but we can also explicitly stop at 1000 if you want.
$edgesProcessed = 0;
$maxEdges       = 1000;

foreach ($edges as $edge) {
    if ($edgesProcessed >= $maxEdges) {
        break;
    }
    $edgesProcessed++;

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

    // Union-by-size: ensure leftLeader is the bigger group
    if ($size[$leftLeader] < $size[$rightLeader]) {
        [$leftLeader, $rightLeader] = [$rightLeader, $leftLeader];
    }

    // Merge right into left
    $leaders[$rightLeader] = $leftLeader;
    $size[$leftLeader]    += $size[$rightLeader];
    unset($size[$rightLeader]);
}

// Now $size contains the sizes of all circuits
$sizes = array_values($size);
rsort($sizes);

$answer = $sizes[0] * $sizes[1] * $sizes[2];

echo "Answer: $answer\n";