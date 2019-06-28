<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("//user", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/new", methods={"GET","POST"})
     */
    public function new(Request $request, UserService $userService): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('company')->getData() === null & ($form->get('firstName')->getData() === null || $form->get('lastName')->getData() === null)) {
                $form->addError(new FormError('Vous devez soit entrer vos Nom et prénom, soit donner le nom de votre entreprise.'));

                return $this->render('user/new.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }

            $userService->saveNewUser($user);

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(User $user): Response
    {
        if(!$user === $this->getUser() & !$this->isGranted("ROLE_ADMIN")) {
            $this->addFlash("warning","Vous n'avez pas accès aux autres utlisateurs.");

            return $this->redirectToRoute("home");
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, User $user): Response
    {
        if(!$user === $this->getUser() & !$this->isGranted("ROLE_ADMIN")) {
            $this->addFlash("warning", "Vous n'avez pas accès aux autres utlisateurs.");

            return $this->redirectToRoute("home");
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if(!$user === $this->getUser() & !$this->isGranted("ROLE_ADMIN")) {
            $this->addFlash("Vous n'avez pas accès aux autres utlisateurs.");

            return $this->redirectToRoute("home");
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index');
    }
}
