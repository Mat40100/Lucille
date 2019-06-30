<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\File;
use App\Entity\Product;
use App\Entity\User;
use App\Form\UserType;
use App\Service\FileService;
use App\Service\ProductService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
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


    /**
     * @Route("/download/{product}-bill-{bill}")
     */
    public function downloadBill(Product $product, Bill $bill, FileController $controller, FileService $fileService)
    {
        return $controller->downloadBill($bill,$product,$fileService);
    }

    /**
     * @Route("/download/{product}-file-{file}")
     */
    public function downloadFile(Product $product, File $file, FileController $controller, FileService $fileService)
    {
        return $controller->downloadFile($file,  $product, $fileService);
    }

    /**
     * @Route("/download/{product}-devis-{devis}")
     */
    public function downloadDevis(Product $product, Devis $devis, FileController $controller, FileService $fileService)
    {
        return $controller->downloadDevis($devis,$product,$fileService);
    }
}
