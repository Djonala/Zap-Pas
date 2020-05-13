<?php

namespace App\Controller;

use App\Form\ParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserParametersController extends AbstractController
{
    /**
     * Cette fonction permet de changer les paramètres de notification d'un user
     * @Route("/parameters", name="user_parameters")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        // je récupère le user
        $user = $this->getUser();
        // je récupère les données qui lui sont associées en paramètre
        $param = $this->getUser()->getParameters();
        // je récupère les calendriers du user connecté
        $calendriers = $user->getCalendriers();
        // j'instancie mon formulaire
        $form = $this->createForm(ParametersType::class, $param);
        $form->handleRequest($request);
        // si il est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // alors j'enregistre mes nouveaux élèments en bdd
            $param = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($param);
            $entityManager->flush();

            return $this->redirectToRoute('calendrier_index');
        }

        return $this->render('page/parameters.html.twig', [
            'controller_name' => 'UserParametersController',
            'calendriers' => $calendriers,
            'param' => $param,
            'form' => $form->createView(),
            'userEnCours' => $user
        ]);
    }
}
