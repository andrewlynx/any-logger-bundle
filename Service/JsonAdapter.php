<?php

namespace Andrewlynx\Bundle\Service;

class JsonAdapter implements FileReaderInterface
{
    /**
     * @param resource $stream
     *
     * @return string|null
     */
    public function readRecord($stream): ?string
    {
        while(!feof($stream))  {
            $record = fgets($stream);
            if ($record !== false) {

                dd( json_decode($record, true));
            }
        }
    }
}