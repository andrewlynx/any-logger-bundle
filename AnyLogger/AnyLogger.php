<?php

namespace Andrewlynx\Bundle\AnyLogger;

use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Throwable;

class AnyLogger
{
    /**
     * @var string
     */
    private $folder;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->folder = $container->getParameter('kernel.logs_dir');
        $this->fileName = $container->getParameter($this->getParamName(AnyLoggerConstant::FILENAME));
    }

    /**
     * @param string $event
     * @param $data
     */
    public function log(string $event, $data): void
    {
        try {
            file_put_contents(
                $this->createLogName($event),
                json_encode([
                    AnyLoggerConstant::FIELD_DATE => (new DateTime())->format('Y-m-d H:i:s'),
                    AnyLoggerConstant::FIELD_EVENT => $event,
                    AnyLoggerConstant::FIELD_DATA => $data,
                ]).PHP_EOL, FILE_APPEND
            );
        } catch (Throwable $throwable) {
            error_log($throwable->getMessage());
        }
    }

    /**
     * @param string $event
     * @param Throwable $throwable
     */
    public function logException(string $event, Throwable $throwable): void
    {
        try {
            file_put_contents(
                $this->createLogName($event),
                json_encode([
                    AnyLoggerConstant::FIELD_DATE => (new DateTime())->format('Y-m-d H:i:s'),
                    AnyLoggerConstant::FIELD_EVENT => $event,
                    AnyLoggerConstant::FIELD_DATA => [
                        'exception_class' => get_class($throwable),
                        'exception_message' => $throwable->getMessage(),
                        'exception_trace' => $throwable->getTraceAsString(),
                    ],
                ]) . PHP_EOL, FILE_APPEND
            );
        } catch (Throwable $throwable) {
            error_log($throwable->getMessage());
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getParamName(string $name): string
    {
        return AnyLoggerConstant::APP_NAME.'.'.$name;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getFileName(string $name): string
    {
        return AnyLoggerConstant::FILE_PREFIX.$name.AnyLoggerConstant::FILE_EXTENSION;
    }

    /**
     * @param string $event
     *
     * @return string
     *
     * @throws Exception
     */
    private function createLogName(string $event): string
    {
        switch ($this->fileName) {
            case AnyLoggerConstant::NAME_DATE:
                $filename = (new DateTime())->format('Y-m-d');
                break;
            case AnyLoggerConstant::NAME_DATE_EVENT:
                $filename = (new DateTime())->format('Y-m-d').'-'.$event;
                break;
            default:
                $filename = $event;
        }

        return $this->folder.'/'.$this->getFileName($filename);
    }
}