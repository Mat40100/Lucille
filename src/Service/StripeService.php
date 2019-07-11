<?php


namespace App\Service;


use App\Entity\Product;
use Stripe\Error\SignatureVerification;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;

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

    public function checkStripeEndpoint(Request $request)
    {
        $endpoint_secret = getenv('STRIPE_WH_SECRET');

        $payload = @file_get_contents($request);
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch(SignatureVerification $e) {
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        if ($event->type == "payment_intent.succeeded") {
            $intent = $event->data->object;
            printf("Succeeded: %s", $intent->id);
            http_response_code(200);
            exit();
        } elseif ($event->type == "payment_intent.payment_failed") {
            $intent = $event->data->object;
            $error_message = $intent->last_payment_error ? $intent->last_payment_error->message : "";
            printf("Failed: %s, %s", $intent->id, $error_message);
            http_response_code(200);
            exit();
        }
    }
}