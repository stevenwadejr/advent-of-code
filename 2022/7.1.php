<?php

require_once __DIR__ . '/../InputReader.php';
$reader = new InputReader(__DIR__ . '/input/7.txt');

class Dir
{
    public array $files = [];
    public array $directories = [];

    public function __construct(
        public readonly string $name,
        public readonly ?Dir $parent
    ) {}

    public function getDirectory(string $dirName): ?Dir
    {
        $found = array_values(array_filter($this->directories, fn($d) => $d->name === $dirName));
        return $found[0] ?? null;
    }

    public function getFile(string $fileName): ?Dir
    {
        $found = array_values(array_filter($this->files, fn($f) => $f->name === $fileName));
        return $found[0] ?? null;
    }

    public function getSize(): int
    {
        $size = array_sum(array_column($this->files, 'size'));
        $size += array_sum(array_map(fn($d) => $d->getSize(), $this->directories));

        return $size;
    }
}

class File
{
    public function __construct(
        public readonly string $name,
        public readonly int $size,
    ) {}
}

$root = new Dir('/', null);;
$currentNode = null;

$reader->loop(function ($line) use (&$root, &$currentNode) {
    if (preg_match('/^\$ cd (.*)/', $line, $matches)) {
        $dirName = $matches[1];
        if ($dirName === '/') {
            $currentNode = $root;
        } elseif ($dirName === '..') {
            $currentNode = $currentNode->parent ?? $currentNode;
        } else {
            $dir = $currentNode->getDirectory($dirName);
            if ($dir !== null) {
                $currentNode = $dir;
            }
        }
    } elseif (preg_match('/dir (.*)/', $line, $matches)) {
        $dirName = $matches[1];
        if ($currentNode->getDirectory($dirName) === null) {
            $currentNode->directories[] = new Dir($dirName, $currentNode);
        }
    } elseif (preg_match('/([0-9]+) (.*)/', $line, $matches)) {
        $file = new File($matches[2], $matches[1]);
        if ($currentNode->getFile($file->name) === null) {
            $currentNode->files[] = $file;
        }
    }
});

// print_r($root);
// die;
// echo "Root size: " . $root->getSize() . "\n";

$sizeCeiling = 100000;
$sizeSum = 0;

$dirChecker = function (Dir $dir) use (&$sizeCeiling, &$sizeSum, &$dirChecker) {
    $dirSize = $dir->getSize();
    if ($dirSize < $sizeCeiling) {
        $sizeSum += $dirSize;
    }

    foreach ($dir->directories as $directory) {
        $dirChecker($directory);
    }
};

$dirChecker($root);

echo "Total size: $sizeSum\n";