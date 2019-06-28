<?php


namespace App\Service;


use App\Entity\OrphanUser;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class OrphanUserService
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function save(OrphanUser $orphanUser)
    {
        $orphanUser->getProduct()->setOrphanUser($orphanUser);
        $this->entityManager->persist($orphanUser->getProduct());
        $this->entityManager->persist($orphanUser);
        $this->entityManager->flush();
    }

}