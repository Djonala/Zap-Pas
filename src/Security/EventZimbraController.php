<?php

namespace App\Controller;


use App\Manager\CalendarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/test")
*/
class EventZimbraController extends AbstractController
{
    /**
     * @Route("/", name="test-max", methods={"GET","POST"})
     */

    public function getFileJsonCalendar(Request $request) : Response{
        return $this->render('calendrier/calendrier-event.html.twig');
    }


}