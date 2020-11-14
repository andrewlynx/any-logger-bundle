<?php

namespace Andrewlynx\Bundle\Controller;

use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnyLoggerController extends AbstractController
{
    /**
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
            'any-logger/logs.html.twig',
            [
                'result' => $result,
            ]
        );
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