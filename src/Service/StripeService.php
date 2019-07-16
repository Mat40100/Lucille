<?php


namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class StripeService
{
    private $stripe_pk;
    private $security;

    public function __construct($stripe_pk, Security $security)
    {
        $this->stripe_pk = $stripe_pk;
        $this->security =$security;
    }

    public function getPaymentSession(Product $product, EntityManagerInterface $entityManager)
    {
        Stripe::setApiKey(getenv('STRIPE_SK_TEST'));

        $uniqId =  uniqid() . '_' . md5(mt_rand());
        $product->setPurchaseId($uniqId);
        $entityManager->flush();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Traduction',
                'description' => 'Traduction number is '.$product->getId(),
                'amount' => $product->getPrice(),
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'client_reference_id' => $product->getPurchaseId(),
            'success_url' => getenv("DEFAULT_URL").'/pay/success',
            'cancel_url' => getenv("DEFAULT_URL").'/pay/refused',
        ]);

        return $session;
    }

    public function checkWebHooks(Request $request)
    {

    }
}