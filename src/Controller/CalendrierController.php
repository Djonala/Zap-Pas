<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\EventZimbra;
use App\EventSubscriber\CalendarSubscriber;
use App\Form\Calendrier1Type;
use App\Manager\CalendarManager;
use App\Repository\CalendrierRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/calendrier")
 */
class CalendrierController extends AbstractController
{
    /**
     * @Route("/", name="calendrier_index", methods={"GET"})
     * @throws \Exception
     */
    public function index(CalendrierRepository $calendrierRepository): Response
    {
        $user = $this->getUser();
        $calendriers = $user->getCalendriers();

        if (isset($calendriers[0])){
            $id = $calendriers[0]->getId();
           return $this->redirectToRoute('calendrier_show', ['id'=>$id]);
        }
        return $this->render('calendrier/index.html.twig', [
            'userEnCours' => $user
        ]);
    }

    /**
     * @Route("/new", name="calendrier_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $calendriers = $user->getCalendriers();
        $calendrier = new Calendrier();
        $form = $this->createForm(Calendrier1Type::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $managerCal = new CalendarManager($entityManager);
            try {
                $managerCal->initCalendarZimbra($calendrier);
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
            $entityManager->flush();

            return $this->redirectToRoute('calendrier_index');
        }

            return $this->render('calendrier/new.html.twig', [
                'calendrier' => $calendrier,
                'form' => $form->createView(),
                'calendriers' => $calendriers,
                'userEnCours' => $user
            ]);

    }

    /**
     * @Route("/{id}", name="calendrier_show", methods={"GET"})
     * @param Calendrier $calendrier
     * @param CalendrierRepository $calendrierRepository
     * @return Response
     */
    public function show(Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // on recupère l'utilisateur
        $user = $this->getUser();

        // je vérifie qu mon calendrier appartien a l'utilisateurs connecté
        if (!$calendrier->getUsers()->contains($user))  {

            // si non alors je lui dit que je ne trouve pas sa recherche
            throw $this->createNotFoundException();
        }

        // on recupère les calendriers de l'utilisateur
        $calendriers = $user->getCalendriers();
        $ar_events = array();

        //pour tous les calendriers de l'utilisateur
        foreach ($calendriers as $calendar){
            // On récupère tous les évènements du calendrier
            $allEvent = $calendar->getEventsZimbra();
            //pour tous les evenements de ce calendrier
            foreach ($allEvent as $event){
                $isExistInArray = false;

                // pour tous les evenements du tableau
                foreach ($ar_events as $eventOfArray){
                    // s'il est déjà existant dans le tableau
                    if ($event->getMatiere() === $eventOfArray->getMatiere()){
                        $isExistInArray = true;
                        break;
                    }
                }
                if($isExistInArray===false){
                    $ar_events[] = $event;
                }
            }
            unset($allEvent);
        }

        $ar_mailIntervenant = array();
        foreach ($ar_events as $eventOnLoad){
            $isExistInArray = false;
            // pour tous les evenements du tableau
            foreach ($ar_mailIntervenant as $mailIntervenant){
                // s'il est déjà existant dans le tableau
                if ($eventOnLoad->getEmailIntervenant() === $mailIntervenant){
                    $isExistInArray = true;
                    break;
                }
            }
            if($isExistInArray===false){
                $ar_mailIntervenant[] = $eventOnLoad->getEmailIntervenant();
            }
        }

        return $this->render('calendrier/show.html.twig', [
            'calendrier' => $calendrier,
            'calendriers' => $calendriers,
            'ar_events' => $ar_events,
            'userEnCours' => $user,
            'ar_mailIntervenant' => $ar_mailIntervenant
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendrier_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, Calendrier $calendrier): Response
    {
        $user = $this->getUser();
        // je vérifie qu mon calendrier appartien a l'utilisateurs connecté
        if (!$calendrier->getUsers()->contains($user))  {
            // si non alors je lui dit que je ne trouve pas sa recherche
            throw $this->createNotFoundException();
        }
        // je récupère mon formulaire
        $form = $this->createForm(Calendrier1Type::class, $calendrier);
        $form->handleRequest($request);
        $calendriers = $user->getCalendriers();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            if($calendrier->getUrl() != $form->get('url')->getData()){
                // j'accede au repository
                $repo = $em->getRepository(EventZimbra::class);
                // je recupère l'ensemble des objets type eventzimbra en bdd
                $objs_db_eventZimbra = $repo->findAllEventByCalId($calendrier->getId());
                // je les supprime
                foreach ($objs_db_eventZimbra as $objEvent){
                    $em->remove($objEvent);
                }
                // j'initialise un manager de calendrier
                $managerCal = new CalendarManager($em);
                try {
                    // Je recupère les evenements associé et je les créer en base.
                    $managerCal->initCalendarZimbra($calendrier);
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }
//            }
            // Je valide l'envoi à ma bdd
            $em->flush();

            return $this->redirectToRoute('calendrier_index');
        }

        return $this->render('calendrier/edit.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form->createView(),
            'calendriers' => $calendriers,
            'userEnCours' => $user
        ]);
    }

    /**
     * @Route("/{id}", name="calendrier_delete", methods={"DELETE"})
     * En cas de delete d'un calendrier, tous ses enfants seront delete aussi
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Calendrier $calendrier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendrier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendrier_index');
    }



    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @Route("/ohlala", name="ohlala", methods={"POST"})
     */
    public function ohlala(Request $request, \Swift_Mailer $mailer) : Response {

        // sécurité
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //je récupère le mail de l'utilisateur et les données saisie dans le form
        $user = $this->getUser();
        $nom = $user->getnomAndPrenom();
        $motif = $_POST['checkbox'];
        $jour = $_POST['jour'];
        $temps = $_POST['date'];
        $unite = $_POST['unite-temp'];
        $datedeb = $_POST['trip-start'];
        $datefin = $_POST['trip-end'];

        // si le form est submit
        if ($request->isMethod('POST')){
            // si le motif est retard
            if ($motif ==='retard') {
                // alors je complète le corps de mon mail en fonction
                $message = (new \Swift_Message ('Retard'))
                    ->setFrom('centralenanteszappas@gmail.com')
                    ->setTo('slclegras@aol.com')
                    ->setBody(
                        "Bonjour, le " . htmlentities($jour) . " " .
                        htmlentities($nom) . "  " . "va etre en " . htmlentities($motif) . " de " .
                        htmlentities($temps) . htmlentities($unite),
                        'text/html'
                    );
            }
            else {
                // sinon je le comlète en fonction de l'absence
                $message = (new \Swift_Message ('Absence'))
                    ->setFrom('centralenanteszappas@gmail.com')
                    ->setTo('slclegras@aol.com')
                    ->setBody("Bonjour, je serais absent(e) du ".$datedeb. " au ". $datefin,
                    'text/html'
                    );
            }
            //On envoie l'e-mail
            $mailer->send($message);
        }
        // on renvoit sur une page qui prévient que le mail est envoyé et invite a retourner a la page precedente
        return $this->render('calendrier/ohlala.html.twig');
    }
}
