<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question')
            ->add('QuestionType',null,[
                'placeholder' => 'Type de question',
                'attr' => [
                    'class' => 'question-type',
                ],

            ])
            ->add("suggestion",  TextType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'data-role' => 'tagsinput',
                    'class' => 'question-choices',

                ],

            ])
            ->add("correctReponse",  HiddenType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'class' => 'correct-answer',

                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'validation_groups' => false,
        ]);
    }
}
