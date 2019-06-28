<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $encoder;
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $passwordEncoder;
    }

    public function saveNewUser(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user ,$user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }
}