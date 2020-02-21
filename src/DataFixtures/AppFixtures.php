<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new Users();
        $user->setNom('TRISTAN');
        $user->setPrenom('Johanna');
        $user->setEmail('johanna.tristan@gmail.com');
        $user->setRoles(json_encode('ROLE_SUPER_ADMIN'));
        $user->setPassword('motdepasse');

        $manager->persist($user);



        $manager->flush();
    }
}
