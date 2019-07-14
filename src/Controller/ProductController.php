<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\Product;
use App\Form\BillType;
use App\Form\DevisType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileService;
use App\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function show(Product $product, ProductService $productService): Response
    {
        if(!$productService->checkPermission($product)) return $this->redirectToRoute('home');

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    public function edit(Request $request, Product $product, FileService $fileService, ProductService $productService)
    {
        if(!$productService->checkEditable($product)) return $this->render("product/show.html.twig", ['product' => $product]);

        if(!$productService->checkPermission($product)) return $this->redirectToRoute('home');

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($product->getFiles() as $file) {
                $file->setProduct($product);
                $fileService->saveFile($file);
            }

            if ((!$product->getPrice() || !$product->getDevis() ) && $product->getState() != 'En attente') {
                $product->setState('En attente');

                $form->addError(new FormError('Vous ne pouvez pas valider une commande sans mettre de prix et un devis.'));

                return $this->render('product/edit.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
                ]);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_userspace_showproduct', [
                'product' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Product $product, ProductService $productService): Response
    {
        if(!$productService->checkEditable($product)) return $this->render("product/show.html.twig", ['product' => $product]);

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash("success", "Product deleted");
        }

        return $this->redirectToRoute('home');
    }
}
