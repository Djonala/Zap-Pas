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
         $calendar = new Calendrier();
         $calendar->setNom('Web_In_Pulse');
         $calendar->setAdmin('maelys-veguer@onfaitunessai.com');
         $calendar->setFormateurs(['Vincent', 'Pascal', 'Sarah']);
         $calendar->setAdministratifs(['Emilie','Laurence','Laetitia']);
         $calendar->setEventZimbra(['']);
         $calendar->setEventLocal(['']);
         $calendar->setUrl('https://webmail.ec-nantes.fr/home/mailys.veguer@ec-nantes.fr/Web%20in-pulse%20%231.json');
         $managerCal = new Manager\CalendarManager();
        try {

            $managerCal->creationEventsZimbraFC($calendar);
        } catch (\Exception $e) {
           var_dump($e->getMessage());
        }



         $manager->persist($calendar);

        $manager->flush();
    }
}
