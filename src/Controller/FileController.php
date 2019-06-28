<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Product;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends AbstractController
{
    public function downloadFile(File $file, Product $product, FileService $fileService)
    {
        if($file->getProduct() === $product && ($product->getUser() === $this->getUser() || $product->getOrphanUser() != null)) {
            $fileToDownload = $fileService->getFileUrl($file);

            return $this->file($fileToDownload, $file->getName());
        }

        $this->addFlash('warning', "Ce fichier ne vous appartient pas");

        return $this->redirectToRoute('home');
    }

}
