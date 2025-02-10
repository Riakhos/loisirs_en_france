<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAuthenticated = $options['is_authenticated']; // Récupération de l'option personnalisée

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un nom'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez le nom de votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('email', EmailType::class, [                
                'label' => 'Votre adresse mail de contact <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir une adresse mail.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez votre adresse mail de contact'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un numéro de téléphone.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez le numéro de téléphone de votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary d-block mx-auto w-100'],
                'label' => 'Inscription'
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site web de votre établissement',
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez URL de votre site web'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Décrivez ce que vous proposez en quelques mots',
                'attr' => [
                    'maxlength' => 250,
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Présentez votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir l\'adresse de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez l\'adresse de votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir le code postal de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez le code postal de votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la ville de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez la ville de votre établissement'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('region', TextType::class, [
                'label' => 'Région de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer la région de votre établissement.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Indiquez la région de votre établissement'
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
                'label' => 'Inscription'
            ])
        ;
        // Afficher les champs user_email et user_password uniquement si l'utilisateur n'est pas connecté
        if (!$isAuthenticated) {
            $builder
                ->add('user_email', EmailType::class, [
                    'label' => 'Email de connexion <span class="text-danger">*</span>',
                    'label_html' => true,
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez fournir votre email de connexion.'
                        ]),
                        new Email([
                            'message' => 'Adresse email invalide.'
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control color-secondary-custom',
                        'placeholder' => 'Entrez votre email'
                    ],
                    'label_attr' => [
                        'class' => 'form-label color-secondary-custom'
                    ]
                ])
                ->add('user_password', PasswordType::class, [
                    'label' => 'Mot de passe <span class="text-danger">*</span>',
                    'label_html' => true,
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Length([
                            'min' => 6, 
                            'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères.'
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control color-secondary-custom',
                        'placeholder' => 'Entrez votre mot de passe'
                    ],
                    'label_attr' => [
                        'class' => 'form-label color-secondary-custom'
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
            'is_authenticated' => false, // Option par défaut
        ]);
    }
}