<?php

class InputReader
{
    private $handle;

    public function __construct(string $fileName)
    {
        $this->handle = fopen($fileName, "r");
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public function loop(callable $fn, bool $trim = true)
    {
        while (($line = fgets($this->handle)) !== false) {
            $fn($trim ? trim($line) : $line);
        }
    }
}
