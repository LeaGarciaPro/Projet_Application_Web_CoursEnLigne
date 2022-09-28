<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireCreationCoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Titre', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('Partie1', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('SousPartie1', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('Texte1', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('Partie2', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('SousPartie2', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ])
            ->add('Texte2', TextType::class, [
                'required' => false,
                'mapped'=>false,
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
 
    }
}