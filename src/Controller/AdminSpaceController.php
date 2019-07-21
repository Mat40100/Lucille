<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Devis;
use App\Entity\OrphanUser;
use App\Entity\Product;
use App\Entity\User;
use App\Form\BillType;
use App\Form\DevisType;
use App\Form\OrphanUserType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\FileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function clients(UserController $controller, UserRepository $userRepository)
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
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
     * @Route("/user-{user}/delete")
     */
    public function userDelete(Request $request, User $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index');
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
     * @Route("/bill/add/{product}")
     */
    public function uploadBill(Request $request, Product $product, ProductController $controller, FileService $fileService)
    {
        $bill = new Bill();

        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setBill($form->getData());
            $fileService->saveFile($form->getData());
            $this->getDoctrine()->getManager()->flush();


            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('bill&devis/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/devis/add/{product}")
     */
    public function uploadDevis(Request $request, Product $product, ProductController $controller, FileService $fileService)
    {
        $devis = new Devis();

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDevis($form->getData());
            $fileService->saveFile($form->getData());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->request->get('referer'));
        }

        return $this->render('bill&devis/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
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

    /**
     * @Route("/orphan/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function orphanShow(OrphanUser $orphanUser): Response
    {
        return $this->render('orphan_user/show.html.twig', [
            'orphan_user' => $orphanUser,
        ]);
    }

    /**
     * @Route("/orphan/edit/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function orphanEdit(OrphanUser $orphanUser, Request $request): Response
    {
        $form = $this->createForm(OrphanUserType::class, $orphanUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_orphan_edit', [
                'orphan' => $orphanUser->getId(),
            ]);
        }

        return $this->render('orphan_user/edit.html.twig', [
            'orphan_user' => $orphanUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orphan/delete/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function orphanDelete(Request $request, OrphanUser $orphanUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orphanUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orphanUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
