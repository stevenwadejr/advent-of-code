<?php

class InputReader2
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

    public function lines(bool $trim = true): Generator
    {
        while (($line = fgets($this->handle)) !== false) {
            yield $trim ? trim($line) : $line;
        }
    }
}
