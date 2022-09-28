<?php

namespace App\Controller;

use App\Entity\Parents;
use App\Entity\Utilisateur;
use App\Form\ParentsType;
use App\Repository\ParentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/parents")
 */
class ParentsController extends AbstractController
{
    /**
     * @Route("/", name="parents_index", methods={"GET"})
     */
    public function index(ParentsRepository $parentsRepository): Response
    {
        return $this->render('parents/index.html.twig', [
            'parents' => $parentsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="parents_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $parent = new Parents();
        $form = $this->createForm(ParentsType::class, $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur  = new Utilisateur();
            $utilisateur->setEmail($form->get('email')->getData());
            $utilisateur->setRoles(["ROLE_PARENT"]);
            $utilisateur->setPassword($encoder->encodePassword($utilisateur,$form->get('password')->getData()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $parent->setUtilisateur($utilisateur);
            $entityManager->persist($parent);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('parents/new.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parents_show", methods={"GET"})
     */
    public function show(Parents $parent): Response
    {
        return $this->render('parents/show.html.twig', [
            'parent' => $parent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parents_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parents $parent): Response
    {
        $form = $this->createForm(ParentsType::class, $parent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parents_index');
        }

        return $this->render('parents/edit.html.twig', [
            'parent' => $parent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parents_delete", methods={"POST"})
     */
    public function delete(Request $request, Parents $parent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parents_index');
    }
}
