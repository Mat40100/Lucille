<?php


namespace App\Service;


use App\Service\Interfaces\AntiVirusInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Process;

class AntiVirusService implements AntiVirusInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function isFileSafe($filePath, $removeUnsafe = false)
    {
        $process = new Process(['clamdscan', $filePath]);
        $process->setTimeout(10);
        $process->run();

        // On supprime le fichier si virus détecté + demande explicite de suppression
        if ($removeUnsafe && !$process->isSuccessful()) {
            unlink($filePath);
        }

        return $process->isSuccessful();
    }
}