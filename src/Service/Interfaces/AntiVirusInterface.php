<?php


namespace App\Service\Interfaces;


interface AntiVirusInterface
{
    /**
     * Vérifie qu'un fichier est sain en fournissant son chemin d'accès
     *
     * @param string  $filePath     Chemin du fichier à contrôler
     * @param boolean $removeUnsafe Supprimer automatiquement le fichier si celui-ci n'est pas sûr ? (default => false)
     * @return boolean
     */
    public function isFileSafe($filePath, $removeUnsafe = false);
}