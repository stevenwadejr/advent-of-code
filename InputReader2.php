<?php

class InputReader2
{
    private $handle;

    public function __construct(private string $fileName)
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

    public function wholeFile(bool $trim = true): string
    {
        return $trim
            ? trim(file_get_contents($this->fileName))
            : file_get_contents($this->fileName);
    }

    public function csv(): Generator
    {
        $contents = $this->wholeFile();

        $size = strlen($contents);
        $pos = 0;

        while ($pos < $size) {
            $end = strpos($contents, ',', $pos);
            if ($end === false) {
                $cur = substr($contents, $pos);
                if (strlen($cur) > 0) {
                    yield $cur;
                }
                break;
            }

            $cur = substr($contents, $pos, $end - $pos);
            if (strlen($cur) > 0) {
                yield $cur;
            }

            $pos = $end + 1;
        }
    }
}
