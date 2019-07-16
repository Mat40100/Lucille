<?php


namespace App\Service;


use App\Entity\Product;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class MailService
{
    private $mailer;
    private $email;
    private $renderer;
    private $security;

    public function __construct(\Swift_Mailer $mailer, $email, Environment $render, Security $security )
    {
        $this->mailer = $mailer;
        $this->email = $email;
        $this->renderer = $render;
        $this->security = $security;
    }

    public function sendRecoveryMail()
    {
        $message = (new \Swift_Message('Récupération de compte Akatraduction !'))
            ->setFrom($this->email)
            ->setTo($this->security->getUser()->getEmail())
            ->setBody(
                $this->renderer->render(
                // templates/emails/registration.html.twig
                    'mails/passwordRecovery.html.twig',
                    ['user' => $this->security->getUser()]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    public function sendIsValidatedMail(Product $product)
    {
        $message = (new \Swift_Message('Votre commande est maintenant validée.'))
            ->setFrom($this->email)
            ->setTo($product->getUser()->getEmail())
            ->setBody(
                $this->renderer->render(
                // templates/emails/registration.html.twig
                    'mails/validatedMail.html.twig', [
                        'user' => $product->getUser(),
                        'product' => $product
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    public function sendIsFinishedMail(Product $product)
    {
        $message = (new \Swift_Message('Votre commande est maintenant terminée !'))
            ->setFrom($this->email)
            ->setTo($product->getUser()->getEmail())
            ->setBody(
                $this->renderer->render(
                // templates/emails/registration.html.twig
                    'mails/validatedMail.html.twig', [
                        'user' => $product->getUser(),
                        'product' => $product
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}