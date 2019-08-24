<?php


namespace App\Service;


use App\Entity\Product;
use App\Entity\User;
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

    /**
     * Send email with token to recover account's password.
     * @param User $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendRecoveryMail(User $user)
    {
        $message = (new \Swift_Message('Récupération de compte Akatraduction !'))
            ->setFrom($this->email)
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderer->render(
                // templates/emails/registration.html.twig
                    'mails/passwordRecovery.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    /**
     * Send Email when product is validated by admin
     * @param Product $product
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendIsValidatedMail(Product $product)
    {
        if(null === $product->getUser()) {
            return;
        }

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

    /**
     * Send Email when wor on a product is completly over.
     * @param Product $product
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendIsFinishedMail(Product $product)
    {
        if(null === $product->getUser()) {
            return;
        }

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

    /**
     * Send mail when a devis is available.
     * @param Product $product
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendDevisAvailableMail(Product $product)
    {
        if(null === $product->getUser()) {
            return;
        }

        $message = (new \Swift_Message('Votre devis est disponible en ligne.'))
            ->setFrom($this->email)
            ->setTo($product->getUser()->getEmail())
            ->setBody(
                $this->renderer->render(
                // templates/emails/registration.html.twig
                    'mails/devisDispo.html.twig',
                    [
                        'user' => $product->getUser(),
                        'product' => $product
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    /**
     * Send mail when a user is created.
     * @param User $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendNewUserEmail(User $user)
    {
        $message = (new \Swift_Message('Création du compte Akatraduction'))
            ->setFrom($this->email)
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderer->render(
                    'mails/newUser.html.twig',
                    [
                        'user' => $user,
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}