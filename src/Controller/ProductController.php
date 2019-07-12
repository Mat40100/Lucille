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

    public function create(Request $request, FileService $fileService, ProductService $productService): Response
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

    public function show(Product $product): Response
    {
        if (!$this->getUser() === $product->getUser() & !$this->isGranted("ROLE_ADMIN")){
            $this->addFlash("warning", "Vous n'avez pas accès à cet item.");

            return $this->redirectToRoute('home');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    public function myProducts()
    {
        $user = $this->getUser();

        return $this->render("product/index.html.twig", [
            'products' => $user->getProducts()
        ]);

    }

    public function edit(Request $request, Product $product, FileService $fileService)
    {
        if ( $product->getState() != 'En attente' and !$this->isGranted("ROLE_ADMIN")) {
            $this->addFlash("warning", "Vous ne pouvez pas modifier une demande une fois validée, contactez Lucille !");

            return $this->redirectToRoute("app_userspace_showproduct", [
                'product' => $product->getId()
            ]);
        }

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

    public function delete(Request $request, Product $product): Response
    {
        if ($product->getState() != 'En attente' && !$this->isGranted("ROLE_ADMIN")) {
            $this->addFlash("warning", "Vous ne pouvez pas modifier une demande une fois validée, contactez Lucille !");

            return $this->render("product/show.html.twig", [
                'product' => $product
            ]);
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash("success", "Product deleted");
        }

        return $this->redirectToRoute('home');
    }

    public function uploadBill(Request $request, Product $product, FileService $fileService)
    {
        $bill = new Bill();

        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setBill($form->getData());
            $fileService->saveBill($form->getData());

            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('bill&devis/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function uploadDevis(Request $request, Product $product, FileService $fileService)
    {
        $devis = new Devis();

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDevis($form->getData());
            $fileService->saveDevis($form->getData());

            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('bill&devis/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
