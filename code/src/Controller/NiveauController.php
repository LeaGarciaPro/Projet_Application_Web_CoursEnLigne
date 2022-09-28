<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Utilisateur;
use App\Form\NiveauType;
use App\Repository\NiveauFiliereRepository;
use App\Repository\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/niveau")
 */
class NiveauController extends AbstractController
{
    /**
     * @Route("/", name="niveau_index", methods={"GET"})
     */
    public function index(NiveauRepository $niveauRepository): Response
    {
        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
        ]);
    }

    /**
     * @Route("/getfilierebyniveau/{id}", name="niveau_json_index", methods={"GET"})
     * @Route("/inscription/getfilierebyniveau/{id}", name="inscription_niveau_json_index", methods={"GET"})
     */
    public function getFiliereByNiveau(Niveau $niveau,Request $request): Response
    {
        $niveaufiliere = [];
        $user = $this->getUser();
        if ($request->attributes->get('_route') != "inscription_niveau_json_index") {
            /** @var Utilisateur $user */
            if($user->hasRole('ROLE_ENSEIGNANT'))
            {
                $matieres = $user->getEnseignant()->getMatiere();
                foreach($matieres as $matiere) {
                    if($matiere->getNiveau()->getId() ==  $niveau->getId()){
                        $enseignantNiveauFiliere[] = $matiere->getNiveauFiliere()->getId();
                    }
                }
            }
        }


        foreach ($niveau->getNiveaufiliere() as $filiere){
            if($request->attributes->get('_route') == "niveau_json_index"){
                if( in_array($filiere->getId(), $enseignantNiveauFiliere)){
                    $niveaufiliere[$filiere->getId()] = $filiere->getNom();
                }
            }else{
                $niveaufiliere[$filiere->getId()] = $filiere->getNom();
            }
        }
        return $this->json($niveaufiliere);
    }

    /**
     * @Route("/new", name="niveau_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($niveau);
            $entityManager->flush();

            return $this->redirectToRoute('niveau_index');
        }

        return $this->render('niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="niveau_show", methods={"GET"})
     */
    public function show(Niveau $niveau): Response
    {
        return $this->render('niveau/show.html.twig', [
            'niveau' => $niveau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="niveau_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Niveau $niveau): Response
    {
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('niveau_index');
        }

        return $this->render('niveau/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="niveau_delete", methods={"POST"})
     */
    public function delete(Request $request, Niveau $niveau): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($niveau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('niveau_index');
    }
}
