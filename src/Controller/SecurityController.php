<?php

namespace App\Controller;

use App\Form\MotDePasseOublieType;
use App\Form\ResetPasswordType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
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
     */
    public function forgottenPassword(Request $request, UsersRepository $usersRepository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator){
        // on créer le formulaire

        $form = $this->createForm(MotDePasseOublieType::class);

        // On traite le formulaire

        $form->handleRequest($request);

        //Si le formulaire est valide
        if($form->isSubmitted() && $form->isValid()){
            //On récupère les données
                $donnees = $form->getData();

            //On cherche si un utilisateur a cette email
            $user = $usersRepository->findOneByEmail($donnees['email']);

            //si l'utilisateur n'existe pas
            if(!$user){
                //on envoie un message flash
                $this->addFlash('pas de compte');
                $this->redirectToRoute('/login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManagers();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch (\Exception $e){
                $this->addFlash('attention', 'une erreur est survenue : '. $e->getMessage());
                return $this->redirectToRoute('/login');
            }

            // On génère l'URL de réinialisatio, de mot de passe
            $url = $this->generateUrl('app_reset_password', ['token'=> $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // On envoie le message
            $message = (new \Swift_Message ('Mot de passe oublié'))
                ->setForm('votre@adresse.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "<p>Bonjour, </p><p>Une demande de réinitialisation de mot de passe a été éffectuée pour l'appli Zap'Pas. Veuillez cliquer sur le lien suivant : ". $url . '</p>',
                    'text/html'
                );

            //On envoie l'e-mail
            $mailer->send($message);

            //On créer le message flash
            $this->addFlash('message', 'Un e-mail de réinitialisation de mot de passe vous à été envoyé ');

            return$this->redirectToRoute('/login');
            }

        //On envoie vers la page de demande de l'e-email
        return $this->render("security/oubliePass.html.twig", ['emailForm'=> $form->createView()]);
    }

    /**
     * @Route("/reset-pass/{token}", name ="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $form = $this->createForm(ResetPasswordType::class);

        // on cherche l'utilsiateur avec le token fourni
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token'=>$token]);

        if (!$user){
            $this->addFlash('message d\'erreur');
            return $this->redirectToRoute("app_login");

            // Si le formulaire est envoyé en méthode POST

            if ($form->isValid()) {
                $user->setResetToken(null);


                //On chiffre le mot de passe
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $entityManager = $this->getDoctrine()->getManagers();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('message identendique');

                return $this->redirectToRoute('app_login');
            }
        }

    }
}
