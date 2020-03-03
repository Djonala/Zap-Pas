<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Manager\CalendarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class UrlJsonController
 * @package App\Controller
 * @Route("/test, name="api_"
 */
class UrlJsonController extends AbstractController
{
    /**
     * @Route("/url/json", name="url_json")
     */
    public function index()
    {

    }

//    /**
//     * @Route("/articles/liste", name="liste", methods={"GET"})
//     * @throws \Exception
//     */
//    public function getCalendar()
//    {
//        $managerCal = new CalendarManager();
//
//        $tableauJson = $managerCal->creationEventsZimbraFC('https://webmail.ec-nantes.fr/home/mailys.veguer@ec-nantes.fr/Web%20in-pulse%20%231.json')
//                // On instancie la réponse
//        $response = new Response($tableauJson);
//
//            // On ajoute l'entête HTTP
//        $response->headers->set('Content-Type', 'application/json');
//
//            // On envoie la réponse
//        return $response;
//
//
//    }
}
