<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\File;
use App\Entity\Product;
use App\Form\ProductType;
use App\Service\FileService;
use App\Service\MailService;
use App\Service\ProductService;
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
        $user = $this->getUser();

        return $this->render("product/index.html.twig", [
            'products' => $user->getProducts()
        ]);
    }

    /**
     * @Route("/product-{product}")
     */
    public function showProduct(Product $product, ProductController $controller, ProductService $productService)
    {
        return $controller->show($product, $productService);
    }

    /**
     * @Route("/product/create")
     */
    public function createProduct(Request $request, ProductController $controller, FileService $fileService, ProductService $productService)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($product->getFiles() as $file) {
                $file->setProduct($product);
                $fileService->saveFile($file);
            }

            $productService->newProduct($product);

            return $this->redirectToRoute('home');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit-{product}")
     */
    public function editProduct(Product $product, ProductController $controller, Request $request, FileService $fileService, ProductService $productService, MailService $mailService)
    {
        return $controller->edit($request,$product, $fileService, $productService, $mailService);
    }

    /**
     * @Route("/product/delete-{product}")
     */
    public function deleteProduct(Product $product, ProductController $controller, Request $request, ProductService $productService)
    {
        return $controller->delete($request,$product, $productService);
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
