<?php

namespace Andrewlynx\Bundle\Controller;

use Andrewlynx\Bundle\AnyLogger\AnyLogger;
use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/any-logger", name="any_logger_")
 */
class AnyLoggerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $logDir = $this->container->getParameter('kernel.logs_dir');
        $finder = new Finder();
        $filenameRegex = $this->getFilenameRegex();
        $finder->files()
            ->in($logDir)
            ->name($filenameRegex);
        $result = [];

        foreach ($finder as $file) {
            $result[] = [
                'name' => $this->getLogName($file->getFileName()),
                'size' => ceil($file->getSize() / 1024).' kB',
            ];
        }
        arsort($result);

        return $this->render(
            '@AnyLogger/logs.html.twig',
            [
                'result' => $result,
            ]
        );
    }

    /**
     * @Route("/view/{name}", name="view")
     *
     * @param string $name
     *
     * @return Response
     */
    public function view(string $name): Response
    {
        $logDir = $this->container->getParameter('kernel.logs_dir');
        $fileName = $logDir.'/'.AnyLogger::getFileName($name);

        if (!file_exists($fileName)) {
            throw new NotFoundHttpException("Log for {$name} not found!");
        }

        $file = fopen($fileName, 'r');
        while(!feof($file))  {
            $record = fgets($file);
            dd($record);
        }
    }

    /**
     * @return string
     */
    private function getFilenameRegex(): string
    {
        $regex = preg_quote(AnyLoggerConstant::FILE_PREFIX).'.*'.preg_quote(AnyLoggerConstant::FILE_EXTENSION);

        return '/^'.$regex.'$/';
    }

    /**
     * @param string $logName
     * @return string
     */
    private function getLogName(string $logName): string
    {
        return str_replace(
            AnyLoggerConstant::FILE_PREFIX,
            '',
            str_replace(AnyLoggerConstant::FILE_EXTENSION, '', $logName));
    }
}