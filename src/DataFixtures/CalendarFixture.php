<?php

namespace App\DataFixtures;

use App\Entity\Calendrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Manager;


class CalendarFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
//         $calendar = new Calendrier();
//         $calendar->setNom('Web_In_Pulse');
//         $calendar->setUrl('https://webmail.ec-nantes.fr/home/mailys.veguer@ec-nantes.fr/Web%20in-pulse%20%231.json');
//         $managerCal = new Manager\CalendarManager($manager);
//
//        try {
//            $managerCal->initCalendarZimbra($calendar);
//        } catch (\Exception $e) {
//           var_dump($e->getMessage());
//        }
//        $manager->flush();
    }
}
