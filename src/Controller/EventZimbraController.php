<?php

namespace App\Controller;

use App\Entity\EventZimbra;
use App\Form\EventZimbraType;
use App\Repository\CoursZimbraRepository;
use App\Repository\EventZimbraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event/zimbra")
 */
class EventZimbraController extends AbstractController
{
    /**
     * @Route("/", name="event_zimbra_index", methods={"GET"})
     */
    public function index(EventZimbraRepository $coursZimbraRepository): Response
    {
        return $this->render('event_zimbra/parameters.html.twig', [
            'event_zimbras' => $coursZimbraRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="event_zimbra_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $eventZimbra = new EventZimbra();
        $form = $this->createForm(EventZimbraType::class, $eventZimbra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventZimbra);
            $entityManager->flush();

            return $this->redirectToRoute('event_zimbra_index');
        }

        return $this->render('event_zimbra/new.html.twig', [
            'event_zimbra' => $eventZimbra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_zimbra_show", methods={"GET"})
     */
    public function show(EventZimbra $eventZimbra): Response
    {
        return $this->render('event_zimbra/show.html.twig', [
            'event_zimbra' => $eventZimbra,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_zimbra_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EventZimbra $eventZimbra): Response
    {
        $form = $this->createForm(EventZimbraType::class, $eventZimbra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_zimbra_index');
        }

        return $this->render('event_zimbra/edit.html.twig', [
            'event_zimbra' => $eventZimbra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_zimbra_delete", methods={"DELETE"})
     *
     */
    public function delete(Request $request, EventZimbra $eventZimbra): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventZimbra->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventZimbra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_zimbra_index');
    }
}
