<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pay")
 */
class StripeController extends AbstractController
{
    /**
     * @Route("/product/{id}")
     * @IsGranted("ROLE_USER")
     */
    public function redirectToCheckout(Product $product, StripeService $stripeService)
    {
        $paymentSession = $stripeService->getPaymentSession($product);

        return $this->render('Stripe/checkout.html.twig', [
            'CHECKOUT_SESSION_ID' => $paymentSession['id']
        ]);
    }

    /**
     * @Route("/success")
     */
    public function paymentSuccess(Request $request)
    {
        dump($request);
        die();
    }

    /**
     * @Route("/refused")
     */
    public function paymentRefused(Request $request)
    {
        dump($request);
        die();
    }

    /**
     * @Route("/webhooks")
     */
    public function webHooks(StripeService $stripeService)
    {
        $status = $stripeService->checkWebHooks();

        switch($status) {
            case 'Purchase fullfiled' :
                return new Response($status,200 );

                break;

            case 'Invalid payload' || 'Invalid signature' || 'Product not found' || 'Not handled event':
                return new Response($status,400 );

                break;
        }

        return new Response('Not handled case',400 );
    }
}