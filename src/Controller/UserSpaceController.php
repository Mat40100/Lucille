<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\File;
use App\Entity\Livrable;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Service\FileService;
use App\Service\MailService;
use App\Service\ProductService;
use App\Service\StripeService;
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
     * @Route("/delete/{user}")
     */
    public function delete(User $user)
    {
        if(in_array('ROLE_ADMIN',$user->getRoles())) {
            $this->addFlash('warning','Vous ne pouvez pas désactiver le compte administrateur.');

            return $this->redirectToRoute('app_adminspace_users');
        }

        $user->setIsActive(false);
        $this->getDoctrine()->getManager()->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('success', 'Compte désactivé avec succès');
        }
        else {
            $this->addFlash('success', 'Votre compte est désactivé, cette mesure sera prise en compte lors de votre prochaine connection. Vous pourrez inverser le processus en me contactant directement.');
        }

        return $this->redirectToRoute('home');
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

            $this->addFlash('success', 'Votre commande à bien été prise en compte, vous recevrez un mail lorsque le devis sera disponible.');

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

    /**
     * @Route("/download/{product}-livrable-{livrable}")
     */
    public function downloadLivrable(Product $product, Livrable $livrable, FileController $controller, FileService $fileService)
    {
        return $controller->downloadLivrable($livrable, $product, $fileService);
    }
}
