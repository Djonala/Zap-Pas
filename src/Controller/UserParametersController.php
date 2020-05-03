<?php

namespace App\Controller;

use App\Form\ParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserParametersController extends AbstractController
{
    /**
     * @Route("/parameters", name="user_parameters")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $param = $this->getUser()->getParameters();
        $calendriers = $user->getCalendriers();
        $form = $this->createForm(ParametersType::class, $param);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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
        ]);
    }
}
