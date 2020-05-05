<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\Calendrier1Type;
use App\Manager\CalendarManager;
use App\Repository\CalendrierRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('calendrier/index.html.twig', [
            'calendriers' => $calendriers,
            'userEnCours' => $user
        ]);
    }

    /**
     * @Route("/new", name="calendrier_new", methods={"GET","POST"})
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

        // on recupère les calendriers de l'utilisateur
        $calendriers = $user->getCalendriers();


        $allEvent = $calendrier->getEventsZimbra();
        $events = array();
        foreach ($allEvent as $event){
            // Si la matière est déjà dans le tableau
            if (!in_array($event->getMatiere(),$events)){
                $events[] = $event;
            }
        }

        return $this->render('calendrier/show.html.twig', [
            'calendrier' => $calendrier,
            'calendriers' => $calendriers,
            'events' => $events,
            'userEnCours' => $user
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendrier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendrier $calendrier): Response
    {
        $form = $this->createForm(Calendrier1Type::class, $calendrier);
        $form->handleRequest($request);
        $user = $this->getUser();
        $calendriers = $user->getCalendriers();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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


}
