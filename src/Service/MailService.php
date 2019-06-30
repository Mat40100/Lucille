<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\AutowiringTypes\TemplatingServices;
use Symfony\Component\HttpKernel\Tests\DependencyInjection\RendererService;
use Twig\Environment;

class MailService
{
    private $mailer;
    private $email;
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, $email, Environment $render )
    {
        $this->mailer = $mailer;
        $this->email = $email;
        $this->renderer = $render;
    }

    public function sendRecoveryMail(User $user)
    {
        $message = (new \Swift_Message('Email Recovery for Lucille\'s website'))
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
}