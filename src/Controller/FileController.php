<?php

namespace App\Controller;

use App\Entity\File;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @Route("/download/file-{file}")
     */
    public function downloadFile(File $file, FileService $fileService)
    {
        $fileToDownload = $fileService->getFileUrl($file);

        return $this->file($fileToDownload, $file->getName());
    }

}
