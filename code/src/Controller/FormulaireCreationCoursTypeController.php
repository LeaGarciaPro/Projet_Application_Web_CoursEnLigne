<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contenu;
use App\Form\FormulaireCreationCoursType;

class FormulaireCreationCoursTypeController extends AbstractController
{

     /**
     * @Route("/CompteEnseignant/CreationCours", name="CreationCours")
     * @param Request $request
     * @return Response
     */
    public function CreationCours(Request $request):Response{

        $contenu = new Contenu();
        $form = $this->createForm(FormulaireCreationCoursType::class);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $dataArray = [
                'Titre'=> $form->get('Titre')->getData(),
                'Partie1' =>$form->get('Partie1')->getData(),
                'SousPartie1' =>$form->get('SousPartie1')->getData(),
                'Texte1' =>$form->get('Texte1')->getData(),
                'Partie2' =>$form->get('Partie2')->getData(),
                'SousPartie2' =>$form->get('SousPartie2')->getData(),
                'Texte2' =>$form->get('Texte2')->getData(),
            ];
            $dataJson = json_encode($dataArray);
           
            $contenu->setContenuJSON($dataJson);
            
            $em->persist($contenu);
            $em->flush();

        return $this->redirectToRoute('main');
        }
        return $this->render('main/CreationCours.html.twig', [
            "form" => $form ->createView()
        ]);
}

    /**
     * @Route("/CompteEnseignant/VisualiserCours", name="VisualiserCours")
     * @param Request $request
     * @return Response
     */
    public function VisualiserCours():Response{
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(\App\Entity\Contenu::class);
        $content = $repository->findAll();

        return $this->render('main/CoursTemplate.html.twig', [
            "contentview" => $content
        ]);
    }

}