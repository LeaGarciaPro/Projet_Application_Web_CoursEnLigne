<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Parents;
use App\Entity\Utilisateur;
use App\Form\EtudiantType;
use App\Repository\DocumentRepository;
use App\Repository\EtudiantRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\VideoRepository;
use App\Repository\PdfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/etudiant")
 */
class EtudiantController extends AbstractController
{
    /**
     * @Route("/", name="etudiant_index", methods={"GET"})
     */
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="etudiant_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail($form->get('email')->getData());
            $utilisateur->setRoles(["ROLE_ETUDIANT"]);
            $utilisateur->setPassword($encoder->encodePassword($utilisateur, $form->get('password')->getData()));

            /** @var Utilisateur $parent */
            $parent = $this->getUser();
            if ($parent === NULL) {
                $etudiant->setParent(NULL);
            } else {

                $etudiant->setParent($parent);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $etudiant->setUtilisateur($utilisateur);
            $entityManager->persist($etudiant);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etudiant_show", methods={"GET"})
     */
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/show.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etudiant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etudiant $etudiant): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etudiant_delete", methods={"POST"})
     */
    public function delete(Request $request, Etudiant $etudiant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etudiant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etudiant_index');
    }

    /**
     * @Route("/questionnaire/list/{idMatiere}", name="questionnaire_list", methods={"GET"})
     */
    public function getQuestionnaireList(Request $request, QuestionnaireRepository $questionnaireRepository, $idMatiere): Response
    {

        return $this->render('questionnaire/indexEtudiant.html.twig', [
            'questionnaires' => $questionnaireRepository->findBy([
                'Matiere' => $idMatiere,
            ]),
            'parent' => $request->getSession()->get('parent')

        ]);
    }

    /**
     * @Route("/document/list/{idMatiere}", name="document_list", methods={"GET"})
     */
    public function getDocumentList(DocumentRepository $documentRepository, $idMatiere): Response
    {
        return $this->render('document/indexEtudiant.html.twig', [
            'documents' => $documentRepository->findBy([
                'matiere' => $idMatiere
            ]),
        ]);
    }

    /**
     * @Route("/video/list/{idMatiere}", name="video_list", methods={"GET"})
     */
    public function getVideoList(VideoRepository $videoRepository, $idMatiere): Response
    {
        return $this->render('video/indexEtudiant.html.twig', [
            'videos' => $videoRepository->findBy([
                'matiere' => $idMatiere
            ]),
        ]);
    }

    /**
     * @Route("/pdf/list/{idMatiere}", name="pdf_list", methods={"GET"})
     */
    public function getPdfList(PdfRepository $pdfRepository, $idMatiere): Response
    {
        return $this->render('pdf/indexEtudiant.html.twig', [
            'pdfs' => $pdfRepository->findBy([
                'matiere' => $idMatiere
            ]),
        ]);
    }
}
