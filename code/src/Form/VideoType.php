<?php

namespace App\Form;

use App\Entity\Niveau;
use App\Entity\NiveauFiliere;
use App\Entity\Utilisateur;
use App\Entity\Video;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class VideoType extends AbstractType
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
            ->add('videoFile', FileType::class, [
                'required' => false
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
            ->add('matiere');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
