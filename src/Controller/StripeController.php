<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Error\SignatureVerification;
use Stripe\Stripe;
use Stripe\Webhook;
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
     * @Route("/pay/product/{id}")
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
    public function webHooks()
    {
        Stripe::setApiKey('sk_test_W5kwb2cTZxdK74kTOhp2UNZg00zp3ZCZrB');

        $endpoint_secret = getenv('ENDPOINT_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch(SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $id = $event->id;

            $datas = json_decode($session, true);

            $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['purchaseId' => $datas['client_reference_id']]);
            $product->setSucceedPaymentID($id);
            $product->setIsPayed('true');
            $this->getDoctrine()->getManager()->flush();
        }

        http_response_code(200); // PHP 5.4 or greater

        return new Response($id,200 );
    }
}
