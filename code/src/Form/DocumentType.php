<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Niveau;
use App\Entity\NiveauFiliere;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class DocumentType extends AbstractType
{
    private $enseignantNiveau = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
        /** @var Utilisateur $user */
        $user = $this->security->getUser();
        if($user->hasRole('ROLE_ENSEIGNANT'))
        {
            $matieres = $user->getEnseignant()->getMatiere();
            foreach($matieres as $matiere) {
                $this->enseignantNiveau[$matiere->getNiveau()->getNom()] = $matiere->getNiveau()->getId();
            }
        }
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du document'
            ])
            ->add('niveau',ChoiceType::class,[
                'placeholder'=> 'Choisir un niveau',
                'choices' => $this->enseignantNiveau,
                'mapped' => false,
                'required' => false,
            ])
            ->add('niveauFiliere',EntityType::class,[
                'class' => NiveauFiliere::class,
                'placeholder'=> 'Choisir un niveau',
                'mapped' => false,
                'required' => false,
            ])
            ->add('matiere')
            ->add('content', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
