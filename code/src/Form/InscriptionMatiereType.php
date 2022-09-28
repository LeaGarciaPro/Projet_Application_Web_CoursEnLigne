<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Niveau;
use App\Entity\NiveauFiliere;
use App\Entity\Pdf;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionMatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('niveau',EntityType::class,[
                'placeholder'=> 'Choisir un niveau',
                'class' => Niveau::class,
                'mapped' => false,
                'required' => false,
            ])
            ->add('niveauFiliere',EntityType::class,[
                'class' => NiveauFiliere::class,
                'mapped' => false,
                'required' => false,
            ])
            ->add('matiere')
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
            "allow_extra_fields" => true

        ]);
    }
}
