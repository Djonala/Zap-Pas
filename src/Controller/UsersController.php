<?php

namespace App\Controller;

use App\Entity\UserParameters;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * pour voir tout les users
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        // pour ajouter la nav bar, on récupère les calendirer du users connecté
        $userEnCours = $this->getUser();
        $calendriers = $userEnCours->getCalendriers();

        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
            'calendriers' => $calendriers
        ]);
    }

    /**
     * fonction pour créer un user
     * @Route("/new", name="users_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // on créer un nouveau user
        $user = new Users();
        // on fait appel au formulaire
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        // on récupère les calendriers associé au user connecté pour afficher la nav bar
        $userEnCours = $this->getUser();
        $calendriers = $userEnCours->getCalendriers();


        // si le formulaire est valid et envoyé alors on encode le mot de passe
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $paramInit = new UserParameters(false);
            $user->setParameters($paramInit);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'calendriers' => $calendriers
        ]);
    }

    /**
     * fonction pour voir user
     * @Route("/{id}", name="users_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        // on récupère les calenriers du users en cours pour l'affichage de la nav bar
        $userEnCours = $this->getUser();
        $calendriers = $userEnCours->getCalendriers();
        return $this->render('users/show.html.twig', [
            'user' => $user,
            'calendriers' => $calendriers
        ]);
    }

    /**
     * fonction pour modifier un utilisateur
     * @Route("/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        // on récupère le formulaire associé à la classe User et le user que l'on veut modifier
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        //on récupère les calendriers associés au user connecté
        $userEnCours = $this->getUser();
        $calendriers = $userEnCours->getCalendriers();

        // si le formulaire est valide et envoyé alors on enregistre les nouvelle info
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'calendriers' => $calendriers
        ]);
    }

    /**
     * fonction pour supprimer un utilisateur
     * @Route("/{id}", name="users_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users_index');
    }
}
