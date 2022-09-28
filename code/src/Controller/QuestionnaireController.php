<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Entity\QuestionnaireEvaluation;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Form\QuestionnaireNoteType;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireEvaluationRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuestionnaireController extends AbstractController
{
    /**
     * @Route("/CompteEnseignant/questionnaire", name="questionnaire_index", methods={"GET"})
     */
    public function index(QuestionnaireRepository $questionnaireRepository): Response
    {
        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaireRepository->findBy([
                'utilisateur' => $this->getUser()
            ]),
        ]);
    }

    /**
     * @Route("/CompteAdmin/questionnaire", name="questionnaire_index_admin", methods={"GET"})
     */
    public function indexAdmin(QuestionnaireRepository $questionnaireRepository): Response
    {
        return $this->render('questionnaire/indexAdmin.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll()]);
    }

    /**
     * @Route("/CompteEnseignant/questionnaire/reponses/{id}", name="questionnaire_reponses_index", methods={"GET"})
     */
    public function indexQuestionnaireReponses(QuestionnaireEvaluationRepository $questionnaireReponseRepository, Questionnaire $questionnaire): Response
    {
        return $this->render('questionnaire/reponseList.html.twig', [
            'questionnaires' => $questionnaireReponseRepository->findBy([
                'questionnaire' => $questionnaire
            ]),
        ]);
    }

    /**
     * @Route("/CompteEnseignant/questionnaire/reponse/{id}", name="questionnaire_reponse_index", methods={"GET","POST"})
     */
    public function indexQuestionnaireReponse(QuestionnaireEvaluation $questionnaireReponse,ReponseRepository $reponseRepository, Request $request): Response
    {
        $form = $this->createForm(QuestionnaireNoteType::class, $questionnaireReponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionnaireReponse);
            $entityManager->flush();

            return $this->redirectToRoute('questionnaire_index');
        }

        $reponsesResult = $reponseRepository->findBy([
            'utilisateur' => $questionnaireReponse->getUtilisateur(),
        ]);

        foreach ($reponsesResult as $response) {
            $reponses[$response->getQuestion()->getId()]['reponse'] = $response->getReponse();
            $reponses[$response->getQuestion()->getId()]['correctReponse'] = $response->getQuestion()->getCorrectReponse();
        }

        return $this->render('questionnaire/evaluate_questionnaire.html.twig', [
            'form' => $form->createView(),
            'questionnaire' => $questionnaireReponse->getQuestionnaire(),
            'isAnswred' => true,
            'reponse' => $reponses,
        ]);
    }

    /**
     * @Route("/CompteEnseignant/questionnaire/new", name="questionnaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        /** @var Utilisateur $utilisteur */
        $utilisteur = $this->getUser();


        $questionnaire = new Questionnaire();
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()) {
                $questionnaire->setUtilisateur($this->getUser());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('questionnaire_index');
        }

        return $this->render('questionnaire/new.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/CompteEnseignant/questionnaire/{id}", name="questionnaire_show", methods={"GET"})
     * @Route("/CompteEtudiant/questionnaire/{id}", name="questionnaire_show_etudiant", methods={"GET","POST"})
     */
    public function show(Questionnaire $questionnaire, QuestionnaireEvaluationRepository $questionnaireReponseRepository, QuestionRepository $questionRepository, ReponseRepository $reponseRepository, Request $request): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();


        if ($request->attributes->get('_route') == "questionnaire_show_etudiant") {
            $isAnswred = false;
            //check if user already answered to the survey.
            $questionnaireReponse = $questionnaireReponseRepository->findOneBy([
                'utilisateur' => $user,
                'questionnaire' => $questionnaire
            ]);
            if ($questionnaireReponse) {
                $isAnswred = true;
            }
            if (!$questionnaireReponse && $request->isMethod("POST")) {
                $entityManager = $this->getDoctrine()->getManager();
                $questionnaireReponse = new QuestionnaireEvaluation();
                $questionnaireReponse->setQuestionnaire($questionnaire);
                $questionnaireReponse->setUtilisateur($user);

                foreach ($request->request->get('question') as $key => $reponse) {
                    if (is_array($reponse)) {
                        $reponse = implode(",", $reponse);
                    }
                    $question = $questionRepository->findOneBy([
                        'id' => $key
                    ]);
                    $response = $reponseRepository->findOneBy([
                        'question' => $question,
                        'utilisateur' => $questionnaireReponse->getUtilisateur(),
                    ]);
                    if (!$response) {
                        $response = new Reponse();
                    }
                    $response->setQuestion($question);
                    $response->setReponse($reponse);
                    $response->setUtilisateur($user);
                    $entityManager->persist($response);
                }
                $entityManager->persist($questionnaireReponse);
                $entityManager->flush();
                return $this->redirectToRoute('CompteEtudiant');

            }
            $reponses = [];
            if ($isAnswred) {
                $reponsesResult = $reponseRepository->findBy([
                    'utilisateur' => $questionnaireReponse->getUtilisateur(),
                ]);

                foreach ($reponsesResult as $response) {
                    if($response->getQuestion()->getQuestionnaire() == $questionnaire){
                        $reponses[$response->getQuestion()->getId()] = $response->getReponse();
                    }

                }
            }
            return $this->render('questionnaire/show_questionnaire.html.twig', [
                'questionnaire' => $questionnaire,
                'reponse' => $reponses,
                'isAnswred' => $isAnswred,
            ]);

        }
        return $this->render('questionnaire/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);

    }

    /**
     * @Route("/CompteEnseignant/questionnaire/{id}/edit", name="questionnaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Questionnaire $questionnaire): Response
    {
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questionnaire_index');
        }

        return $this->render('questionnaire/edit.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/CompteEnseignant/questionnaire/{id}", name="questionnaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Questionnaire $questionnaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $questionnaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($questionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('questionnaire_index');
    }
}
