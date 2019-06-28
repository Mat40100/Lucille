<?php

namespace App\Controller;

use App\Entity\OrphanUser;
use App\Form\OrphanUser1Type;
use App\Repository\OrphanUserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orphan")
 */
class OrphanController extends AbstractController
{
    /**
     * @Route("/new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orphanUser = new OrphanUser();
        $form = $this->createForm(OrphanUserType::class, $orphanUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orphanUser);
            $entityManager->flush();

            $this->addFlash('success', 'Your request has been saved, you\'ll be contacted soon');
            return $this->redirectToRoute('home');
        }

        return $this->render('orphan_user/new.html.twig', [
            'orphan_user' => $orphanUser,
            'form' => $form->createView(),
        ]);
    }

    public function show(OrphanUser $orphanUser): Response
    {
        return $this->render('orphan_user/show.html.twig', [
            'orphan_user' => $orphanUser,
        ]);
    }

    public function edit(Request $request, OrphanUser $orphanUser): Response
    {
        $form = $this->createForm(OrphanUserType::class, $orphanUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_orphan_edit', [
                'id' => $orphanUser->getId(),
            ]);
        }

        return $this->render('orphan_user/edit.html.twig', [
            'orphan_user' => $orphanUser,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, OrphanUser $orphanUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orphanUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orphanUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }


    public function index(OrphanUserRepository $orphanUserRepository): Response
    {
        return $this->render('orphan_user/index.html.twig', [
            'orphan_users' => $orphanUserRepository->findAll(),
        ]);
    }
}
