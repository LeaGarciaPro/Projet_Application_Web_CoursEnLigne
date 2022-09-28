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
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        $user = $this->getUser();
        if ($user) {
            $roles = $user->getRoles();
            if (in_array("ROLE_ENSEIGNANT", $roles)) {
                return $this->redirectToRoute("CompteEnseignant");
            }
            if (in_array("ROLE_ETUDIANT", $roles)) {
                return $this->redirectToRoute("CompteEtudiant");
            }
            if (in_array("ROLE_PARENT", $roles)) {
                return $this->redirectToRoute("CompteParent");
            }

        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        return $this->render('Accueil.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
