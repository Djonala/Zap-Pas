<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new Users();
        $user->setNom('TRISTAN');
        $user->setPrenom('Johanna');
        $user->setEmail('johanna.tristan@gmail.com');
        $user->setRoles(json_encode('ROLE_SUPER_ADMIN'));
        $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
           'the_new_password'
        ));

        $manager->persist($user);



        $manager->flush();
    }
}
