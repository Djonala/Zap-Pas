<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\EventZimbra;
use App\Repository\EventZimbraRepository;
use Doctrine\DBAL\Types\ObjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * @Route("/api")
 */
class APIController extends AbstractController
{

    /**
     * @Route("/liste/{id}", name="liste", methods={"GET"})
     */
    public function liste($id=null)
    {
        // on récupère l'utilisateur en cours
        $user = $this->getUser();

        // si l'id na pas été renseigné, on essaye de récupérer le 1er calendrier de l'utilisateur
        if($id === null ){
            $calendriers = $user->getCalendriers();
            if(isset($calendriers[0])){
                $cal = $calendriers[0];
            }
        // sinon on recupère le calendrier correspondant à l'id
        } else {
            $repo = $this->getDoctrine()->getRepository(Calendrier::class);
            $cal = $repo->find($id);
        }

        // si un calendrier a bien été recupéré
        if (isset($cal)) {

            // on recupère les events associé à ce calendrier
            $repo = $this->getDoctrine()->getRepository(EventZimbra::class);

            //On recupère la liste des events du calendrier
            $events = $repo->findAllEventByCalId($cal->getId());

            $result=array();

            // Je selectionne dans mon tableau les elements qui m'interesse
            foreach ($events as $event){
                $result[] = [
                    "title" => $event->getMatiere(),
                    "start" => $event->getDateDebutEvent()->format('Y-m-d H:i:s'),
                    "end" => $event->getDateFinEvent()->format('Y-m-d H:i:s')
                ];
            }
        // sinon, on renvoi un champ vide
        } else {
            $result[] = "";
        }
            //On specifie qu'on utilise un encodeur en json
            $encoders = [new JsonEncoder()];

            //On instancie le "normaliseur" pour convertir la collection en tableau
            $normalizers = [new ObjectNormalizer()];

            //on instancie le convertisseur
            $serializer = new Serializer($normalizers, $encoders);

            //on convertit en json
            $jsonContent = $serializer->serialize($result, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['calendrier'],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

            //On instancie la réponse
            $response = new Response($jsonContent);

            // On ajoute l'entête http
            $response->headers->set('Content-Type', 'application/json');

            //On envoi la reponse
            return $response;



    }


}
