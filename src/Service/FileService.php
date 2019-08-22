<?php


namespace App\Service;


use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\File;
use App\Entity\Livrable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FileService
{
    private $files_directory;
    private $bills_directory;
    private $devis_directory;
    private $livrables_directory;
    private $fileSystem;
    private $entityManager;
    private $antiVirus;
    private $session;

    public function __construct(SessionInterface $session, $files_directory, $bills_directory, $devis_directory, $livrables_directory, EntityManagerInterface $entityManager, Filesystem $fileSystem, AntiVirusService $antiVirusService)
    {
        $this->files_directory = $files_directory;
        $this->bills_directory = $bills_directory;
        $this->devis_directory = $devis_directory;
        $this->livrables_directory = $livrables_directory;
        $this->fileSystem = $fileSystem;
        $this->entityManager = $entityManager;
        $this->antiVirus = $antiVirusService;
        $this->session = $session;
    }

    /**
     * @param File $file || Devis $devis || Bill $bill
     */
    public function getDir($file)
    {
        if ($file instanceof File) {
            $dir = $this->files_directory;
        }

        if ($file instanceof Bill) {
            $dir = $this->bills_directory;
        }

        if ($file instanceof Devis) {
            $dir = $this->devis_directory;
        }

        if ($file instanceof Livrable) {
            $dir = $this->livrables_directory;
        }

        return $dir;
    }

    public function getFileUrl($file)
    {
        $url = $this->getDir($file)."/".$file->getEncodedName();

        return $url;
    }

    /**
     * @param $file
     * @return bool
     */
    public function saveFile($file)
    {
        if (!$file->getId()) {
            try {
                $file->getFile()->move(
                    $this->getDir($file),
                    $file->getEncodedName()
                );

                if(!$this->antiVirus->isFileSafe($this->getFileUrl($file), true)) {
                    $this->session->getFlashBag()->add('danger', 'Votre fichier '.$file->getName().' est marqué comme contenant un virus, l\'upload n\'a donc pas été effectué.');

                    return false;
                }

            } catch (FileException $e) {
                return false;
            }

            $this->entityManager->persist($file);

            return true;
        }
    }

    /**
     * @param $file
     */
    public function removeFile($file)
    {
        $file->setProduct(null);
        $this->entityManager->remove($file);
        $this->entityManager->flush();

        try {
            $this->fileSystem->remove(['symlink', $this->getDir($file), $file->getEncodedName().".".$file->getExtension()]);
        } catch (FileException $e) {

        }
    }
}