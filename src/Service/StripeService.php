<?php


namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Error\SignatureVerification;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;
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
        $user = $this->security->getUser();

        $customer = $user->getStripeCustomer();

        if ($customer === null) {
            $customer = Customer::create([
                'email' => $user->getEmail()
            ])->id;

            $user->setStripeCustomer($customer);
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Traduction',
                'description' => 'Traduction number is '.$product->getId(),
                'amount' => $product->getPrice(),
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'customer' => $customer,
            'success_url' => getenv("DEFAULT_URL").'/pay/success/'.$product->getId(),
            'cancel_url' => getenv("DEFAULT_URL").'/pay/refused/'.$product->getId(),
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

            return 'Invalid payload';
        } catch(SignatureVerification $e) {
            // Invalid signature

            return 'Invalid signature';
        }

        switch ($event->type) {

            case 'checkout.session.completed':
                $session = $event->data->object;

                $paymentIntent = PaymentIntent::retrieve($session->payment_intent);
                $product = $this->entityManager->getRepository(Product::class)->findOneBy(['paymentIntent' => $session->payment_intent]);

                if (null === $product) return 'Product not found';

                if ($paymentIntent->status === 'succeeded') {

                    $product->setIsStripePayed('true');

                    $this->entityManager->flush();
                }

                return 'Purchase fullfiled';

                break;
        }

        return 'Not handled event';
    }
}