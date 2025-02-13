<?php

namespace App\Form;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une note.'
                    ])
                ],
                'attr' => [
                    'class' => 'd-flex flex-wrap justify-content-between color-secondary-custom mb-2'
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez laisser un commentaire.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 250,
                        'minMessage' => 'Votre commentaire doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre commentaire ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9.,!?\'"\s-]+$/u', // Autorise lettres, chiffres, espaces et certains signes de ponctuation
                        'message' => 'Le commentaire contient des caractères non autorisés.'
                    ]),
                ],
                'attr' => [
                    'maxlength' => 250,
                    'class' => 'form-control',
                    'placeholder' => 'Laissez nous un commentaire',
                    'autocomplete' => 'off',
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
                'label' => 'Ajouter une note',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100 color-primary-custom',
                    'data-loading-text' => 'Chargement...'
                ]
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