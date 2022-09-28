<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Image;
use App\Entity\Utilisateur;
use App\Form\ImageType;
use App\Form\InscriptionMatiereType;
use App\Repository\EtudiantRepository;
use App\Repository\ParentsRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contenu;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class MainController extends AbstractController
{
    /**
     * @Route("/main/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/", name="Accueil")
     */
    public function Accueil()
    {
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
        return $this->render('main/accueil.html.twig');
    }

    /**
     * @Route("/CompteEnseignant", name="CompteEnseignant")
     */
    public function CompteEnseignant()
    {
        //dump($this->getUser());
        //die();
        return $this->render('main/CompteEnseignant.html.twig');

    }


    /**
     * @Route("/CompteEnseignant/DeposerPDF", name="DeposerPDF")
     */
    public function DeposerPDF()
    {

        return $this->render('main/DeposerPDF.html.twig');
    }

    /**
     * @Route("/CompteEnseignant/CreationQuizz", name="CreationQuizz")
     */
    public function CreationQuizz()
    {

        return $this->render('main/CreationQuizz.html.twig');
    }

    /**
     * @Route("/CompteEtudiant", name="CompteEtudiant")
     * @Route("/CompteEtudiant/{idMatiere}", name="CompteEtudiantMatiere")
     */
    public function CompteEtudiant(EtudiantRepository $etudiantRepository, $idMatiere = NULL)
    {
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->getUser();
        $userMatiere = [];
        foreach ($utilisateur->getEtudiant()->getMatiere() as $matiere) {
            if ($idMatiere === NULL || $matiere->getId() == $idMatiere) {
                $userMatiere[$matiere->getId()] = [
                    'id' => $matiere->getId(),
                    'nom' => $matiere->getNom()
                ];
            }

        }
        return $this->render('main/CompteEtudiant.html.twig', [
            'matieres' => $userMatiere
        ]);
    }


    /**
     * @Route("/Inscription", name="inscription")
     */
    public function Inscription()
    {

        return $this->render('main/Inscription.html.twig');
    }

    /**
     * @Route("/CompteParent", name="CompteParent")
     */
    public function CompteParent(EtudiantRepository $etudiantRepository)
    {
        /** @var \App\Entity\Utilisateur $user */
        $parent = $this->getUser();
        $etudiants = $etudiantRepository->findBy([
            'parent' => $parent
        ]);
        return $this->render('main/CompteParent.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    /**
     * @Route("/childAuthenticator/{idUtilisateur}", name="child_authenticator")
     */
    public function childAuthenticator($idUtilisateur = NULL, Request $request, EtudiantRepository $etudiantRepository)
    {
        /** @var \App\Entity\Utilisateur $user */
        $parent = $this->getUser();
        /** @var \App\Entity\Etudiant $etudiants */
        $etudiants = $etudiantRepository->findOneBy([
            'utilisateur' => $idUtilisateur,
            'parent' => $parent
        ]);
        $user = $etudiants->getUtilisateur();
        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $session = $this->get('session');
        $session->set('_security_main', serialize($token));
        $session->set('parent', $parent);

        return $this->redirectToRoute('CompteEtudiant');
    }

    /**
     * @Route("/switchToParent", name="switch_to_parent")
     */
    public function switchToParent(Request $request, EtudiantRepository $etudiantRepository)
    {
        $user = $this->getUser()->getEtudiant()->getParent();

        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $session = $this->get('session');
        $session->set('_security_main', serialize($token));
        $session->set('parent', null);

        return $this->redirectToRoute('CompteParent');

    }

    /**
     * @Route("/inscription/matiere", name="inscription_matiere")
     */
    public function inscriptionMatiere(Request $request)
    {
        /** @var Enseignant $enseignant */
        $enseignant = $this->getUser()->getEnseignant();
        $form = $this->createForm(InscriptionMatiereType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('CompteEnseignant');
        }

        return $this->render('enseignant/inscription_matiere.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/CompteAdmin", name="CompteAdmin")
     */
    public function CompteAdmin()
    {
        return $this->render('main/CompteAdmin.html.twig');

    }

}