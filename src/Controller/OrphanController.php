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

            $this->addFlash('success', 'Votre commande est en attente de validation, vous serez bientôt contacté.');
            return $this->redirectToRoute('home');
        }

        return $this->render('orphan_user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
