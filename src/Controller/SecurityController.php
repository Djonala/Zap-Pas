<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\MotDePasseOublieType;
use App\Form\ResetPasswordType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');

    }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function forgottenPassword(Request $request, UsersRepository $usersRepository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        // on créer le formulaire
        $form = $this->createForm(MotDePasseOublieType::class);
        // On traite le formulaire
        $form->handleRequest($request);
        //Si le formulaire est post
        if ($request->isMethod('POST')) {

            // je récupère la saisie du formulaire
            $email = $request->request->get('mot_de_passe_oublie',['email']);


            $entityManager = $this->getDoctrine()->getManager();
            // je vérifie que le mail correspond a un mail en bdd ou alors je mets null
            $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);
            /* @var $user Users */

            // si le user existe j'envoie le mail et je redirige vers l'accueil
            if ($user === null) {
                $this->addFlash('notice', "Si l'adresse mentionnée est exacte, un mail vous a été envoyé");
                return $this->redirectToRoute('app_login');
            }
            // On génère un token
            $token = $tokenGenerator->generateToken();
            // je gère les exceptions
            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('attention', 'une erreur est survenue : ' . $e->getMessage());
                return $this->redirectToRoute('login');
            }
            // On génère l'URL de réinialisatio, de mot de passe
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // On envoie le message
            $message = (new \Swift_Message ('Mot de passe oublié'))
                ->setFrom('centralenanteszappas@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "<p>Bonjour, </p><p>Une demande de réinitialisation de mot de passe a été effectuée pour l'appli Zap'Pas. Veuillez cliquer sur le lien suivant : " . $url . '</p>',
                    'text/html'
                );
            //On envoie l'e-mail
            $mailer->send($message);

            //On créer le message flash
            $this->addFlash('notice', 'Si l\'adresse mentionnée est exacte, un mail vous a été envoyé');
            return $this->redirectToRoute('app_login');
        }
        //On envoie vers la page de demande de l'e-email
        return $this->render("security/oubliePass.html.twig", ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset-pass/{token}", name ="app_reset_password")
     * @param $token
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        // on cherche l'utilsiateur avec le token fourni
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token' => $token]);

        if (!$user) {
            $this->addFlash('message', 'une erreur est survenue');
            return $this->redirectToRoute('app_login');
        }
        // Si le formulaire est envoyé en méthode POST

        if ($form->isSubmitted() && $form->isValid())  {
            $user->setResetToken(null);
            //On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message','mot de passe changé avec succès');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'user' => $user,
            'resetPass' => $form->createView(),

        ]);
    }

}


