<?php


namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class ProductService
{
    private $entityManager;
    private $security;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, Security $security, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->session = $session;
    }

    public function newProduct(Product $product)
    {
        $product->setUser($this->security->getUser());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function checkPermission(Product $product)
    {
        if (!$this->security->getUser() === $product->getUser()){
            $this->session->getFlashBag()->add("warning", "Vous n'avez pas accès à cette commande.");

            return false;
        }

        return true;
    }

    public function checkEditable(Product $product)
    {
        if ($product->getState() != 'En attente' && !$this->isGranted("ROLE_ADMIN")) {
            $this->session->getFlashBag()->add("warning", "Vous ne pouvez pas modifier une demande une fois validée, contactez Lucille !");

            return false;
        }

        return true;
    }

}