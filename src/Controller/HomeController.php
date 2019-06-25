<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/new")
     */
    public function devis()
    {
        if (!$this->isGranted('ROLE_USER')) {

            return $this->render('home/newBill.html.twig');
        }

        return $this->redirectToRoute('app_product_new');
    }
}
