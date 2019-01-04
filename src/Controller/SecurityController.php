<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // créer dans SecurityController, la route qui va nous permettre d'afficher les infos de l'utilisateur connecté
    // Restreindre l'accès à ce contrôleur aux utilisateurs connectés

    /**
    * @Route("/user/infos", name="userInfo")
    */
    public function showConnectedUser() {
        // pour restreindre l'accès au contrôleur aux seuls utilisateurs connectés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // récupérer l'utilisateur connecté
        $user = $this->getUser();
        return $this->render('security/user.html.twig', ['moi' => $user]);
    }
}
