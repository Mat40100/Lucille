<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\OrphanUser;
use App\Entity\Product;
use App\Form\OrphanUserType;
use App\Service\FileService;
use App\Service\OrphanUserService;
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
     * @Route("/new")
     */
    public function new(Request $request, FileService $fileService, OrphanUserService $userService): Response
    {
        $orphanUser = new OrphanUser();
        $form = $this->createForm(OrphanUserType::class, $orphanUser);

        if($request->getMethod() === 'POST') {
            $fileService->removeEmptyFilesInRequest($request);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($orphanUser->getProduct()->getFiles() as $file) {

                $file->setProduct($orphanUser->getProduct());
                $fileService->saveFile($file);
            }
            $this->getDoctrine()->getManager()->persist($orphanUser->getProduct());
            $userService->save($orphanUser);

            $this->addFlash('success', 'Votre commande est en attente de validation, vous serez bientôt contacté.');
            return $this->redirectToRoute('home');
        }

        return $this->render('orphan_user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
