<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user2 = new User();

        $user->setEmail('mathieu.dolhen@gmail.com')
            ->setFirstName('Mathieu')
            ->setLastName('Dolhen')
            ->setPhoneNumber('0650425183')
            ->setBillingAddress('61 Av de l\'aérodrome, 40100 Dax')
            ->setPassword('$argon2i$v=19$m=1024,t=2,p=2$ZUdzNFF4M0tKUVNvTHNRVQ$z1buhjJlOXm/niZBp4hCgwVvqv+0XqDbhMJ2cVA3eDw');

        $user2->setEmail('lu.gauthier64@gmail.com')
            ->setFirstName('Lucille')
            ->setLastName('Gauthier')
            ->setPhoneNumber('0608004599')
            ->setBillingAddress('Ascain')
            ->setPassword('$argon2i$v=19$m=1024,t=2,p=2$ZUdzNFF4M0tKUVNvTHNRVQ$z1buhjJlOXm/niZBp4hCgwVvqv+0XqDbhMJ2cVA3eDw');

        $manager->persist($user);

        $user2 = new User();

        $user2->setEmail('lgauthier@akatraductions.com')
            ->setFirstName('Lucille')
            ->setLastName('Dolhen')
            ->setPhoneNumber('0608004599')
            ->setBillingAddress('Ascain')
            ->setPassword('$argon2i$v=19$m=1024,t=2,p=2$ZUdzNFF4M0tKUVNvTHNRVQ$z1buhjJlOXm/niZBp4hCgwVvqv+0XqDbhMJ2cVA3eDw');

        $manager->persist($user2);

        $manager->flush();
    }
}
