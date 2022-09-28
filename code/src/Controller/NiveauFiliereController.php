<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\NiveauFiliere;
use App\Form\NiveauFiliereType;
use App\Repository\NiveauFiliereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/niveauFiliere")
 */
class NiveauFiliereController extends AbstractController
{
    /**
     * @Route("/", name="niveau_filiere_index", methods={"GET"})
     */
    public function index(NiveauFiliereRepository $niveauFiliereRepository): Response
    {
        return $this->render('niveau_filiere/index.html.twig', [
            'niveau_filieres' => $niveauFiliereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="niveau_filiere_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $niveauFiliere = new NiveauFiliere();
        $form = $this->createForm(NiveauFiliereType::class, $niveauFiliere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($niveauFiliere);
            $entityManager->flush();

            return $this->redirectToRoute('niveau_filiere_index');
        }

        return $this->render('niveau_filiere/new.html.twig', [
            'niveau_filiere' => $niveauFiliere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="niveau_filiere_show", methods={"GET"})
     */
    public function show(NiveauFiliere $niveauFiliere): Response
    {
        return $this->render('niveau_filiere/show.html.twig', [
            'niveau_filiere' => $niveauFiliere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="niveau_filiere_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NiveauFiliere $niveauFiliere): Response
    {
        $form = $this->createForm(NiveauFiliereType::class, $niveauFiliere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('niveau_filiere_index');
        }

        return $this->render('niveau_filiere/edit.html.twig', [
            'niveau_filiere' => $niveauFiliere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="niveau_filiere_delete", methods={"POST"})
     */
    public function delete(Request $request, NiveauFiliere $niveauFiliere): Response
    {
        if ($this->isCsrfTokenValid('delete'.$niveauFiliere->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($niveauFiliere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('niveau_filiere_index');
    }

}
