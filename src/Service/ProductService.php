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

    /**
     * @param Product $product
     */
    public function newProduct(Product $product)
    {
        $product->setUser($this->security->getUser());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function checkPermission(Product $product)
    {
        if (!$this->security->getUser() === $product->getUser()){
            $this->session->getFlashBag()->add("warning", "Vous n'avez pas accès à cette commande.");

            return false;
        }

        return true;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function checkEditable(Product $product)
    {
        if ($product->getState() != 'En attente' && !$this->security->isGranted("ROLE_ADMIN")) {
            $this->session->getFlashBag()->add("warning", "Vous ne pouvez pas modifier une demande une fois validée, contactez Lucille !");

            return false;
        }

        return true;
    }

    /**
     * @param Product $product
     * @param string $oldState
     * @return bool
     */
    public function isValidated(Product $product, string $oldState)
    {
        if($product->getState() != 'En attente') {

            if ($oldState === 'En attente') {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @param Product $product
     * @param $oldState
     * @return bool
     */
    public function isFinished(Product $product, $oldState)
    {
        if($product->getState() === 'Terminée') {

            if ($oldState != 'Terminée') {
                return true;
            }

            return false;
        }

        return false;
    }

    public function isPayable(Product $product)
    {
        if($product->getState() === ('En attente')) {
            $this->session->getFlashBag()->add("warning", "Vous ne pouvez pas payer une commande si elle n'est pas au moins validée.");

            return false;
        }

        return true;
    }
}