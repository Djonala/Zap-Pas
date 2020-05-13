<?php

namespace App\EventSubscriber;

use App\Repository\EventZimbraRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * @Route("/calendarSubscriber")
 */
class CalendarSubscriber implements EventSubscriberInterface
{
    private $eventZimbraRepository;
    private $router;

    public function __construct(
        EventZimbraRepository $eventZimbraRepository,
        UrlGeneratorInterface $router
    ) {
        $this->eventZimbraRepository= $eventZimbraRepository;
        $this->router = $router;
    }
    /**
     * @Route("/calendarSubscriber/get", name="get-calendarSubscriber", methods={"GET"})
     */
    public static function getSubscribedEvents() {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }


    public static function load(){

    }

    /**
     * @Route("/calendarSubscriber/set", name="set-calendarSubscriber", methods={"POST"})
     * @param CalendarEvent $calendar
     */
    public function onCalendarSetData(CalendarEvent $calendar) {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $events = $this->eventZimbraRepository
            ->createQueryBuilder('event-zimbra')
            ->where('event-zimbra.date_debut_event BETWEEN :start and :end OR booking.date_fin_event BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($events as $event) {
            // this create the events with your data (here booking data) to fill calendar
            $eventZimbra = new Event(
                $event->getTitle(),
                $event->getDateDebutEvent(),
                $event->getDateFinEvent() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $eventZimbra->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $eventZimbra->addOption(
                'url',
                $this->router->generate('booking_show', [
                    'id' => $event->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($eventZimbra);
        }
    }
}