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
use App\Service\MailService;
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
    public function users(UserRepository $userRepository)
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findBoolActive(true),
            'title' => 'clients',
            'pathLabel' => 'Voir les utilisateurs désactivés',
            'activePath' => $this->generateUrl('app_adminspace_seeinactiveusers')
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
     * @Route("/users/inactives")
     */
    public function seeInactiveUsers(UserRepository $repository)
    {
        return $this->render('user/index.html.twig', [
            'users' => $repository->findBoolActive(false),
            'title' => 'clients désactivés',
            'pathLabel' => 'Voir les utilisateurs actifs',
            'activePath' => $this->generateUrl('app_adminspace_users')
        ]);
    }

    /**
     * @Route("/user-{user}/reactivate")
     */
    public function reactiveUser(User $user, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['id' => $user->getId()]);
        $user->setIsActive(true);
        $this->getDoctrine()->getManager()->flush();


        $this->addFlash('success', 'Le compte est réactivé avec succès.');
        return $this->redirectToRoute('app_adminspace_users');
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
        if ($user === $this->getUser()) {
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer le compte administrateur');

            return $this->redirectToRoute('app_adminspace_users');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash('warning', 'Le compte est supprimé avec succès.');

        return $this->redirectToRoute('app_adminspace_users');
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

            if(null !== $product->getBill()) {
                $fileService->removeFile($product->getBill());
            }

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
    public function uploadDevis(Request $request, Product $product, FileService $fileService, MailService $mailService)
    {
        $devis = new Devis();

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(null !== $product->getDevis()) {
                $fileService->removeFile($product->getDevis());
            }

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
        $product->setIsOffLinePayed(true);
        $this->getDoctrine()->getManager()->flush();

        $this->redirectToRoute('app_userspace_showproduct', [
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

            return $this->redirectToRoute('app_adminspace_orphanshow', [
                'id' => $orphanUser->getId(),
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
