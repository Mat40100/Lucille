<?php


namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProductService
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function newProduct(Product $product)
    {
        $product->setUser($this->security->getUser());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

}