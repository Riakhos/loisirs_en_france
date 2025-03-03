<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'constraints' => $options['is_edit'] ? [] : [
                new NotBlank([
                    'message' => 'Veuillez fournir un mot de passe.'
                ]),
                new Length([
                    'min' => 12,
                    'max' => 30,
                    'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/',
                    'message' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
                ])
            ],
            'first_options'  => [
                'label' => 'Votre mot de passe',
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Choisissez votre mot de passe',
                    'autocomplete' => 'off'
                ],
                'hash_property_path' => 'password'
            ],
            'second_options' => [
                'label' => 'Confirmer mot de passe',
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Confirmez votre mot de passe',
                    'autocomplete' => 'off'
                ]
            ],
            'mapped' => false,
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
                'class' => 'form-label color-secondary-custom text-center'
            ]
        ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réinitialiser le mot de passe',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-40',
                    'data-loading-text' => 'Chargement...'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}