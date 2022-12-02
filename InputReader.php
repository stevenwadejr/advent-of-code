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

    public function loop(callable $fn)
    {
        while (($line = fgets($this->handle)) !== false) {
            $fn(trim($line));
        }
    }
}
