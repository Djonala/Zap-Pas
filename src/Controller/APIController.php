<?php

namespace App\Controller;

use App\Repository\EventZimbraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/event/liste", name="liste", methods={"GET"})
     */
    public function liste(EventZimbraRepository $eventsRepo)
    {
        //On recupère la liste des events du calendrier
        $events = $eventsRepo->findAll();
        dd($events);
    }


}
