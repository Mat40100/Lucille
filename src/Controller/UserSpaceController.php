<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Product;
use App\Service\FileService;
use App\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 */
class UserSpaceController extends AbstractController
{
    /**
     * @Route("/show")
     */
    public function show(UserController $controller)
    {
        return $controller->show($this->getUser());
    }

    /**
     * @Route("/edit")
     */
    public function edit(UserController $controller, Request $request)
    {
        return $controller->edit($request, $this->getUser());
    }

    /**
     * @Route("/delete")
     */
    public function delete()
    {
        ##TODO
    }

    /**
     * @Route("/password/reset")
     */
    public function changePassword(Request $request)
    {
        ##TODO
    }

    /**
     * @Route("/products")
     */
    public function showOwnedProducts()
    {
        return $this->render('product/index.html.twig', [
            'products' => $this->getUser()->getProducts()
        ]);
    }

    /**
     * @Route("/product-{product}")
     */
    public function showProduct(Product $product, ProductController $controller)
    {
        return $controller->show($product);
    }

    /**
     * @Route("/product/create")
     */
    public function createProduct(Request $request, ProductController $controller, FileService $fileService, ProductService $productService)
    {
        return $controller->create($request, $fileService, $productService);
    }

    /**
     * @Route("/product/edit-{product}")
     */
    public function editProduct(Product $product, ProductController $controller, Request $request)
    {
        return $controller->edit($request,$product);
    }

    /**
     * @Route("/product/delete-{product}")
     */
    public function deleteProduct(Product $product, ProductController $controller, Request $request)
    {
        return $controller->delete($request,$product);
    }


    public function downloadBill()
    {

    }

    /**
     * @Route("/download/{product}-{file}")
     */
    public function downloadFile(Product $product, File $file, FileController $controller, FileService $fileService)
    {
        return $controller->downloadFile($file,  $product, $fileService);
    }

    public function downloadDevis()
    {

    }
}
