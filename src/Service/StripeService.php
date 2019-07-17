<?php


namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Error\SignatureVerification;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class StripeService
{
    private $stripe_pk;
    private $security;
    private $entityManager;

    public function __construct($stripe_pk, Security $security, EntityManagerInterface $entityManager)
    {
        $this->stripe_pk = $stripe_pk;
        $this->security =$security;
        $this->entityManager= $entityManager;
    }

    public function getPaymentSession(Product $product)
    {
        Stripe::setApiKey(getenv('STRIPE_SK_TEST'));


        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Traduction',
                'description' => 'Traduction number is '.$product->getId(),
                'amount' => $product->getPrice(),
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'customer_email' => $this->security->getUser()->getEmail(),
            'success_url' => getenv("DEFAULT_URL").'/pay/success',
            'cancel_url' => getenv("DEFAULT_URL").'/pay/refused',
        ]);

        $product->setPaymentIntent($session['payment_intent']);

        $this->entityManager->flush();

        return $session;
    }

    public function checkWebHooks()
    {
        Stripe::setApiKey(getenv('STRIPE_SK_TEST'));

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

            return false;
        } catch(SignatureVerification $e) {
            // Invalid signature

            return false;
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'charge.succeeded') {
            $session = $event->data->object;

            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['paymentIntent' => $session->payment_intent]);

            $product->setReceiptUrl($session->receipt_url);
            $product->setPaymentCharge($session->id);

            $product->setIsPayed('true');
            $this->entityManager->flush();
        }

        return true;
    }
}