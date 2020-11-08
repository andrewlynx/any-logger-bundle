<?php

namespace Andrewlynx\Bundle\AnyLogger;

use Andrewlynx\Bundle\Constant\AnyLogger as AnyLoggerConstant;
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
     *
     * @throws Exception
     */
    public function log(string $event, $data): void
    {
        try {
            file_put_contents($this->getFileName($event), json_encode([
                    'date' => (new DateTime())->format('Y-m-d H:i:s'),
                    'event' => $event,
                    'data' => $data,
                ]).PHP_EOL, FILE_APPEND);
        } catch (Throwable $th) {
            error_log($th->getMessage());
        }
    }

    /**
     * @param string $event
     *
     * @return string
     *
     * @throws Exception
     */
    private function getFileName(string $event): string
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

        return $this->folder.'/'.$filename.'.log';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getParamName(string $name): string
    {
        return AnyLoggerConstant::APP_NAME.'.'.$name;
    }
}