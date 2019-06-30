<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private $encoder;
    private $entityManager;
    private $security;

    public function __construct(Security $security, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $passwordEncoder;
        $this->security = $security;
    }

    public function saveNewUser(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user ,$user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

    public function resetPassword(User $user) {
        $user->setPassword($this->encoder->encodePassword($user ,$user->getPassword()));
        $user->setResetToken(null);

        $this->entityManager->flush();
    }

    public function sendToken(User $user)
    {
        $length = 35;
        $token = bin2hex(random_bytes($length));
        $user->setResetToken($token);

        $this->entityManager->flush();
    }
}