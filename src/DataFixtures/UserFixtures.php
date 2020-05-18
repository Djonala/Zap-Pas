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
        $user->setNom('Super');
        $user->setPrenom('Admin');
        $user->setEmail('centralenanteszappas@gmail.com');
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $user->setParameters($param);
        $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
           'WIP2020'
        ));
        $user->setResetToken(null);
        $manager->persist($user);



        $manager->flush();
    }
}
