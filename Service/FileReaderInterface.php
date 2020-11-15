<?php

namespace Andrewlynx\Bundle\Service;

interface FileReaderInterface
{
    /**
     * @param resource $stream
     *
     * @return string|null
     */
    public function readRecord($stream): ?string;
}