<?php

namespace Andrewlynx\Bundle\Service;

class LogReaderService
{
    /**
     * @var FileReaderInterface
     */
    public $adapter;

    /**
     * @param FileReaderInterface $adapter
     */
    public function __construct(FileReaderInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $filename
     * @return callable
     */
    public function read(string $filename): callable
    {
        return function () use ($filename): void {
            try {
                $this->readFile($filename);
            } catch (Throwable $exception) {
                printf('Failed to read: %s', $exception->getMessage());
            }
        };
    }

    /**
     * @param string $filename
     */
    private function readFile(string $filename): void
    {
        $file = fopen($filename, 'r');

        if ($file === false) {
            throw new RuntimeException(sprintf('Failed to open file "%s" for reading.', $filename));
        }

        while ($record = $this->adapter->readRecord($file)) {
            echo $record;
        }

        fclose($file);
    }
}