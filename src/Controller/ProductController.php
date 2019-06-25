<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/product/new", methods={"GET","POST"})
     */
    public function create(Request $request, FileService $fileService): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($product->getFiles() as $item) {

                $item->setProduct($product);
                $fileService->saveFile($item);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
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

    /**
     * @Route("/myproducts", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function myProducts()
    {
        $user = $this->getUser();

        return $this->render("product/index.html.twig", [
            'products' => $user->getProducts()
        ]);

    }

    /**
     * @Route("/product/{id}/edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Product $product): Response ##TODO : FIX EDITING FILES
    {
        if ($product->getIsValid() & !$this->isGranted("ROLE_ADMIN")) {
                $this->addFlash("warning", "Vous ne pouvez pas modifier une demande une fois validée, contactez Lucille !");

                return $this->redirectToRoute("product/show.html.twig", [
                    'id' => $product->getId()
                ]);
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_product_show', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($product->getIsValid() & !$this->isGranted("ROLE_ADMIN")) {
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
}
