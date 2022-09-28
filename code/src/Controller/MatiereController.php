<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Utilisateur;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

/**
 * @Route("/matiere")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="matiere_index", methods={"GET"})
     */
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/getMatiereByNiveauAndFiliere/{idNiveau}/{idFiliere}", name="get_matiere_by_niveau_and_filiere_new", methods={"GET","POST"})
     * @Route("/inscription/getMatiereByNiveauAndFiliere/{idNiveau}/{idFiliere}", name="inscription_get_matiere_by_niveau_and_filiere_new", methods={"GET","POST"})
     */
    public function getMatiereByNiveauAndFiliere(Request $request,$idNiveau,$idFiliere, MatiereRepository $matiereRepository): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        if ($request->attributes->get('_route') != "inscription_get_matiere_by_niveau_and_filiere_new") {

            /** @var Utilisateur $user */
            if($user->hasRole('ROLE_ENSEIGNANT'))
            {

                $matieres = $user->getEnseignant()->getMatiere();

                foreach($matieres as $matiere) {
                    $enseignantMatiere[] = $matiere->getId();
                }

            }
        }
        $matieres = $matiereRepository->findBy([
            'niveau'=> $idNiveau,
            'niveauFiliere' => $idFiliere,
        ]);

        $matiereJson = [];
        if ($matieres) {
            foreach ($matieres as $matiere) {
                if ($request->attributes->get('_route') == "get_matiere_by_niveau_and_filiere_new") {
                    if (in_array($matiere->getId(), $enseignantMatiere)) {
                        $matiereJson[$matiere->getId()] = $matiere->getNom();
                    }
                } else {
                    $matiereJson[$matiere->getId()] = $matiere->getNom();

                }
            }
        }
        return $this->json($matiereJson);
    }

    /**
     * @Route("/new", name="matiere_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('matiere_index');
        }

        return $this->render('matiere/new.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matiere_show", methods={"GET"})
     */
    public function show(Matiere $matiere): Response
    {
        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="matiere_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Matiere $matiere): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matiere_index');
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matiere_delete", methods={"POST"})
     */
    public function delete(Request $request, Matiere $matiere): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($matiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matiere_index');
    }
}
