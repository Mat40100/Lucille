<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
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

    public function downloadBill(Bill $bill, Product $product, FileService $fileService)
    {
        if($bill->getProduct() === $product && ($product->getUser() === $this->getUser() || $product->getOrphanUser() != null)) {
            $fileToDownload = $fileService->getFileUrl($bill);

            return $this->file($fileToDownload, $bill->getName());
        }

    }

    public function downloadDevis(Devis $devis, Product $product, FileService $fileService)
    {
        if($devis->getProduct() === $product && ($product->getUser() === $this->getUser() || $product->getOrphanUser() != null)) {
            $fileToDownload = $fileService->getFileUrl($devis);

            return $this->file($fileToDownload, $devis->getName());
        }
    }
}
