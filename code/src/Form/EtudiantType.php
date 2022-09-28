<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Niveau;
use App\Entity\NiveauFiliere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom', null, [
                'label' => 'Prénom'
            ])
            ->add('email',EmailType::class,[
                'mapped'  => false
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas les mêmes',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank,
                ]
            ])
            ->add('niveau',EntityType::class,[
                'placeholder'=> 'Choisir un niveau',
                'class' => Niveau::class,
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
            'data_class' => Etudiant::class,
        ]);
    }
}
