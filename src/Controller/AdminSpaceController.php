<?php

namespace App\Controller;

use App\Entity\OrphanUser;
use App\Entity\Product;
use App\Entity\User;
use App\Form\BillType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\FileService;
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
    public function products(ProductRepository $repository)
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
     * @Route("/bill/add/{product}")
     */
    public function uploadBill(Request $request, Product $product, ProductController $controller, FileService $fileService)
    {
        return $controller->uploadBill($request, $product, $fileService);
    }

    /**
     * @Route("/devis/add/{product}")
     */
    public function uploadDevis(Request $request, Product $product, ProductController $controller, FileService $fileService)
    {
        return $controller->uploadDevis($request, $product, $fileService);
    }

    /**
     * @Route("/product/validate/{product}")
     */
    public function validateProduct(Product $product)
    {
        $product->setIsValid(true);
        $this->getDoctrine()->getManager()->flush();

        $this->redirectToRoute('app_adminspace_seeProduct', [
            'product' => $product->getId()
        ]);
    }

    /**
     * @Route("/product/confirmPayment/{product}")
     */
    public function confirmPayment(Product $product)
    {
        $product->setIsPayed(true);
        $this->getDoctrine()->getManager()->flush();

        $this->redirectToRoute('app_adminspace_seeProduct', [
            'product' => $product->getId()
        ]);
    }
}
