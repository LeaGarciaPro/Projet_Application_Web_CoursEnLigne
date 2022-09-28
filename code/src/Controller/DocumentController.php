<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Image;
use App\Entity\Utilisateur;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    /**
     * @Route("/CompteEnseignant/document/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findBy([
                'utilisateur' => $this->getUser()
            ]),
        ]);
    }

    /**
     * @Route("/CompteAdmin/document/", name="document_index_admin", methods={"GET"})
     */
    public function indexAdmin(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/indexAdmin.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/CompteEnseignant/document/new", name="document_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();

        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user){
                $document->setUtilisateur($user);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('document_index');
        }
        return $this->render('document/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/CompteEnseignant/document/{id}", name="document_show", methods={"GET"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
            'contents' => json_decode($document->getContent(), true),
        ]);
    }

    /**
     * @Route("/CompteEtudiant/document/{id}", name="document_show_etudiant", methods={"GET"})
     */
    public function showEtudiant(Document $document): Response
    {
        return $this->render('document/showEtudiant.html.twig', [
            'document' => $document,
            'contents' => json_decode($document->getContent(), true),
        ]);
    }


    /**
     * @Route("/CompteEnseignant/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/CompteEnseignant/{id}", name="document_delete", methods={"POST"})
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_index');
    }
}
