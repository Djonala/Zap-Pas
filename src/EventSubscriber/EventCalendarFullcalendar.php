<?php


namespace App\EventSubscriber;
use App\Repository\EventZimbraRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Class EventCalendarFullcalendar
 * @package App\EventSubscriber
 */
class EventCalendarFullcalendar extends AbstractController
{

    /**
     * @Route("/")
     */
    public function loadEvent()
    {
        $repoEventZimbra = $this->getDoctrine()->getRepository(EventZimbraRepository::class);
        $events = $repoEventZimbra->findAll();


    }
}