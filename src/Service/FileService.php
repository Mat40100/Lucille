<?php


namespace App\Service;


use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService
{
    private $files_directory;
    private $bills_directory;
    private $entityManager;

    public function __construct($files_directory, $bills_directory, EntityManagerInterface $entityManager)
    {
        $this->files_directory = $files_directory;
        $this->bills_directory = $bills_directory;
        $this->entityManager = $entityManager;
    }

    public function getFileUrl(File $file)
    {
        $url = $this->files_directory."/".$file->getEncodedName();

        return $url;
    }

    public function saveFile(File $file)
    {
        $uploadedFile = $file->getFile();
        $fileName = $this->generateUniqueFileName().'.'.$uploadedFile->guessExtension();

        $file->setName($uploadedFile->getClientOriginalName());
        $file->setEncodedName($fileName);

        try {
            $uploadedFile->move(
                $this->files_directory,
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $this->entityManager->persist($file);
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}