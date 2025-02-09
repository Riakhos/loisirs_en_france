<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un nom'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le nom de votre établissement'
                ]
            ])
            ->add('email', EmailType::class, [                
                'label' => 'Votre adresse mail',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir une adresse mail.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez votre adresse mail'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un numéro de téléphone.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le numéro de téléphone de votre établissement'
                ]
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site web de votre établissement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez URL de votre site web'
                ]
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Décrivez ce que vous proposez en quelques mots',
                'attr' => ['maxlength' => 100]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir l\'adresse de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez l\'adresse de votre établissement'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir le code postal de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le code postal de votre établissement'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la ville de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez la ville de votre établissement'
                ]
            ])
            ->add('region', TextType::class, [
                'label' => 'Région de votre établissement',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la région de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez la région de votre établissement'
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('captcha', CheckboxType::class, [
                'label' => 'Vérifiez que vous êtes un humain.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez vérifier que vous êtes un humain.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-40'
                ],
                'label' => 'Inscription'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}