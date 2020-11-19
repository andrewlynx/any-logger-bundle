<?php

namespace Andrewlynx\Bundle\Service;

use Andrewlynx\Bundle\Constant\AnyLoggerConstant;

class JsonAdapter implements FileReaderInterface
{
    /**
     * @param resource $stream
     */
    public function readRecord($stream): void
    {
        $this->printHeader();
        while(!feof($stream))  {
            $jsonRecord = fgets($stream);
            if ($jsonRecord !== false) {
                $record = json_decode($jsonRecord, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo sprintf('<tr>Failed to decode record JSON: %s</tr>', json_last_error_msg());
                }

                echo sprintf(
                    '<tr>%s%s%s</tr>',
                    $this->wrapInTag($record[AnyLoggerConstant::FIELD_DATE]),
                    $this->wrapInTag($record[AnyLoggerConstant::FIELD_EVENT]),
                    $this->wrapInTag($this->formatArray($record[AnyLoggerConstant::FIELD_DATA]))
                );
            }
        }
        $this->printFooter();
    }

    /**
     *
     */
    private function printHeader(): void
    {
        echo '<table><thead><th></th><th></th><th></th></thead><tbody>';
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private function wrapInTag(string $data): string
    {
        return sprintf('<td>%s</td>', $data);
    }

    /**
     * @param string|array $data
     *
     * @return string
     */
    private function formatArray($data): string
    {
        if (is_array($data)) {
            return sprintf('<pre>%s</pre>', json_encode($data, JSON_PRETTY_PRINT));
        }

        return $data;
    }

    /**
     *
     */
    private function printFooter(): void
    {
        echo '</tbody></table>';
    }
}