<?php

namespace App\DataFixtures;

use App\Entity\UserParameters;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{

    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $param = new UserParameters(true);
        $manager->persist($param);
        $user = new Users();
        $user->setNom('TRISTAN');
        $user->setPrenom('Johanna');
        $user->setEmail('johanna.tristan@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setParameters($param);
        $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
           'winwip'
        ));
        $user->setResetToken(null);
        $manager->persist($user);



        $manager->flush();
    }
}
