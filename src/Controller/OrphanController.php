<?php

namespace App\Controller;

use App\Entity\OrphanUser;
use App\Form\OrphanUserType;
use App\Service\FileService;
use App\Service\OrphanUserService;
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
    public function new(Request $request, FileService $fileService, OrphanUserService $userService): Response
    {
        $orphanUser = new OrphanUser();
        $form = $this->createForm(OrphanUserType::class, $orphanUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($orphanUser->getProduct()->getFiles() as $file) {

                $file->setProduct($orphanUser->getProduct());
                $fileService->saveFile($file);
            }
            $userService->save($orphanUser);

            $this->addFlash('success', 'Your request has been saved, you\'ll be contacted soon');
            return $this->redirectToRoute('home');
        }

        return $this->render('orphan_user/new.html.twig', [
            'orphan_user' => $orphanUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/orphan/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(OrphanUser $orphanUser): Response
    {
        return $this->render('orphan_user/show.html.twig', [
            'orphan_user' => $orphanUser,
        ]);
    }

    /**
     * @Route("/admin/orphan/edit/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(OrphanUser $orphanUser, Request $request): Response
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
     * @Route("/admin/orphan/delete/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, OrphanUser $orphanUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orphanUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orphanUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
