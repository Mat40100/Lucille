<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function redirectToCheckout(Product $product, StripeService $stripeService, ProductService $productService)
    {
        if(!$productService->checkPermission($product)) $this->redirectToRoute('home');


        if($product->getIsStripePayed() || $product->getIsPayed()) {

            $this->addFlash('warning', 'Cette commande a déjà été réglée.');

            return $this->redirectToRoute('app_userspace_showproduct', [
                'product' => $product
            ]);
        }
        $paymentSession = $stripeService->getPaymentSession($product);

        return $this->render('Stripe/checkout.html.twig', [
            'CHECKOUT_SESSION_ID' => $paymentSession['id']
        ]);
    }

    /**
     * @Route("/success/{product}")
     * @IsGranted("ROLE_USER")
     */
    public function paymentSuccess(Product $product, ProductService $productService)
    {
        if(!$productService->checkPermission($product)) $this->redirectToRoute('home');

        if($product->getIsStripePayed()) {

            return $this->render('Stripe/goodPayment.html.twig', [
                'product' => $product
            ]);
        }

        return $this->render('Stripe/badPayment.html.twig', [
            'message' => 'Nous n\'avons pas reçu le retour de paiement de votre commande, vérifiez la présence d\'une facture dans votre boîte mail, et contacez Lucille',
            'product' => $product
        ]);
    }

    /**
     * @Route("/refused/{product}")
     * @Route("ROLE_USER")
     */
    public function paymentRefused(Product $product, ProductService $productService)
    {
        if(!$productService->checkPermission($product)) $this->redirectToRoute('home');

        return $this->render('Stripe/badPayment.html.twig', [
            'message' => 'Votre commande n\'a pas été payée, vous pouvez faire un nouvel essai depuis la page de votre commande.',
            'product' => $product
        ]);
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