<?php

namespace App\Form;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score', ChoiceType::class, [
                'choices' => [
                    '1 ★' => 1,
                    '1.5 ★✩' => 1.5,
                    '2 ★★' => 2,
                    '2.5 ★★✩' => 2.5,
                    '3 ★★★' => 3,
                    '3.5 ★★★✩' => 3.5,
                    '4 ★★★★' => 4,
                    '4.5 ★★★★✩' => 4.5,
                    '5 ★★★★★' => 5,
                ],
                'expanded' => true, // Pour afficher des boutons radio
                'multiple' => false, // Sélection unique
                'label' => 'Note',
                'attr' => [
                    'class' => 'd-flex flex-wrap justify-content-between color-secondary-custom mb-2'
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => true,
                'attr' => [
                    'maxlength' => 250,
                    'class' => 'form-control',
                    'placeholder' => 'Laissez nous un commentaire'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez laisser un commentaire.'
                    ]),
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('captcha', CheckboxType::class, [
                'label' => 'Vérifiez que vous êtes un humain.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez vérifier que vous êtes un humain.'
                    ])
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100 color-primary-custom'
                ],
                'label' => 'Ajouter une note'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}