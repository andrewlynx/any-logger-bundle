<?php

namespace Andrewlynx\Bundle\Controller;

use Andrewlynx\Bundle\AnyLogger\AnyLogger;
use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
use Andrewlynx\Bundle\Service\LogReaderService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
        $filenameRegex = $this->getFileNameRegex();
        $finder->files()
            ->in($logDir)
            ->name($filenameRegex);
        $result = [];

        foreach ($finder as $file) {
            $logName = $this->getLogName($file->getFileName());
            $result[] = [
                'name' => $logName,
                'size' => $this->getFileSizeInKb($file->getSize()).' kB',
                'remove' => $this->generateRemoveForm($logName)->createView(),
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
     * @param string           $name
     * @param LogReaderService $logReader
     *
     * @return Response
     */
    public function view(string $name, LogReaderService $logReader): Response
    {
        $fileName =$this->getFullFileName($name);

        if (!file_exists($fileName)) {
            throw new NotFoundHttpException("Log for {$name} not found!");
        }

        $file = fopen($fileName, 'r');
        $fileSize = $this->getFileSizeInKb(fstat($file)['size']);
        fclose($file);

        // Prevent parsing large files that may cause "Out Of Memory" error
        if ($fileSize > $this->container->getParameter(AnyLogger::getParamName('parse_json_size_limit'))) {

            return new BinaryFileResponse($fileName);
        } else {

            return StreamedResponse::create($logReader->read($fileName), Response::HTTP_OK, ['Content-Type' => 'text/html']);
        }
    }

    /**
     * @Route("/delete/{name}", name="delete")
     *
     * @param string  $name
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function delete(string $name, Request $request): RedirectResponse
    {
        $form = $this->generateRemoveForm($name)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filesystem = new Filesystem();
            $fileName =$this->getFullFileName($name);

            $filesystem->remove($fileName);

            return $this->redirectToRoute('any_logger_index');
        }

        throw new Exception('You don\'t have an access to this page');
    }

    /**
     * @return string
     */
    private function getFileNameRegex(): string
    {
        $regex = preg_quote(AnyLoggerConstant::FILE_PREFIX).'.*'.preg_quote(AnyLoggerConstant::FILE_EXTENSION);

        return '/^'.$regex.'$/';
    }

    /**
     * @param int $sizeInBytes
     * 
     * @return int
     */
    private function getFileSizeInKb(int $sizeInBytes): int
    {
        return ceil($sizeInBytes / 1024);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getFullFileName(string $fileName): string
    {
        $logDir = $this->container->getParameter('kernel.logs_dir');

        return $logDir.'/'.AnyLogger::getFileName($fileName);
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

    /**
     * @param string $logName
     *
     * @return Form
     */
    private function generateRemoveForm(string $logName): Form
    {
        return $this->get('form.factory')
            ->createNamed(
                $logName,
                'Symfony\Component\Form\Extension\Core\Type\FormType',
                [],
                [
                    'action' => $this->generateUrl('any_logger_delete', ['name' => $logName]),
                ]
            )
            ->add('Delete', SubmitType::class);
    }
}