<?php

namespace App\Controller;

use App\Entity\OrphanUser;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminSpaceController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render("");
    }

    /**
     * @Route("/users")
     */
    public function clients(UserController $controller, UserRepository $repository)
    {
        return $controller->index($repository);
    }

    /**
     * @Route("/user-{user}")
     */
    public function seeUser(User $user, UserController $controller)
    {
        return $controller->show($user);
    }

    /**
     * @Route("/user-{user}/edit")
     */
    public function editUser(User $user, UserController $controller, Request $request)
    {
        return $controller->edit($request, $user);
    }

    /**
     * @Route("/products")
     */
    public function products(ProductController $controller, ProductRepository $repository)
    {
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/product-{product}")
     */

    public function seeProduct(Product $product, ProductController $controller)
    {
        return $controller->show($product);
    }

    /**
     * @Route("/facture/add/{product}")
     */
    public function uploadBill(Product $product)
    {

    }

    /**
     * @Route("/bill/add/{product}")
     */
    public function uploadDevis(Product $product)
    {

    }

    /**
     * @Route("/product/validate/{product}")
     */
    public function validateProduct(Product $product)
    {

    }

    /**
     * @Route("/product/confirmPayment/{product}")
     */
    public function confirmPayment(Product $product)
    {

    }
}
