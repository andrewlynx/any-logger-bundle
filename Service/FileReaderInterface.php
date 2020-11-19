<?php

namespace Andrewlynx\Bundle\Service;

interface FileReaderInterface
{
    /**
     * @param resource $stream
     */
    public function readRecord($stream): void;
}