<?php


namespace App\Service;


use App\Entity\Product;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    private $baseUrl;
    private $skTest;
    private $pkTest;

    public function __construct($baseUrl, $pkTest)
    {
        $this->baseUrl = $baseUrl;
        $this->skTest = getenv('STRIPE_SK_TEST');
        $this->pkTest = $pkTest;
    }

    public function getIntent(Product $product)
    {
        Stripe::setApiKey($this->skTest);

        $intent = PaymentIntent::create([
            'amount' => $product->getPrice(),
            'currency' => 'eur',
        ]);

        return $intent;
    }
}