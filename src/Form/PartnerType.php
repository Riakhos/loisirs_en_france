<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
                    new Length([
                        'min' =>5,
                        'max' => 50,
                        'minMessage' => 'Le nom de l\'établissement doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de l\'établissement ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-zÀ-ÖØ-öø-ÿ0-9'&.-]{2,50}$/",
                        'message' => "Ceci n'est pas un prénom valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le nom de votre établissement',
                    'autocomplete' => 'off'
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
                    new Email([
                        'message' => 'L\'adresse email n\'est pas valide.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez votre adresse mail de contact',
                    'autocomplete' => 'off'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone de votre établissement <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un numéro de téléphone.'
                    ]),
                    new Length([
                        'min' =>9,
                        'max' => 20,
                        'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^\+?[0-9]{1,4}[\s.-]?(\(?\d{1,3}\)?)?[\s.-]?\d{1,4}[\s.-]?\d{1,4}$/',
                        'message' => "Ceci n'est pas un numéro de téléphone valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le numéro de téléphone de votre établissement',
                    'autocomplete' => 'off',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100',
                    'data-loading-text' => 'Chargement...'
                ],
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site web de votre établissement',
                'default_protocol' => 'https', // ✅ Ajout du protocole par défaut
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'URL est obligatoire.'
                    ]),
                    new Url([
                        'message' => 'Veuillez entrer une URL valide.'
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'L\'URL ne peut pas dépasser 255 caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^(https?:\/\/)?([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,})(\/.*)?$/',
                        'message' => 'L\'URL doit être valide et contenir un domaine correct.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez URL de votre site web',
                    'autocomplete' => 'off',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Décrivez ce que vous proposez en quelques mots',
                'attr' => [
                    'maxlength' => 250,
                    'class' => 'form-control',
                    'placeholder' => 'Présentez votre établissement',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez décrire votre établissement.'
                    ]),
                    new Length([
                        'max' => 250,
                        'maxMessage' => 'Votre présentation ne peut pas dépasser 250 caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9.,!?\'"\s\-]+$/u',
                        'message' => 'La présentation contient des caractères non autorisés.'
                    ])
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
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s,.\'-]+$/',
                        'message' => 'L\'adresse contient des caractères non autorisés.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez l\'adresse de votre établissement',
                    'autocomplete' => 'off',
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
                    new Length([
                        'min' => 4,
                        'max' => 10,
                        'minMessage' => 'Le code postal doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le code postal ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{4,10}$/',
                        'message' => 'Le code postal doit contenir uniquement des chiffres (4 à 10 caractères).'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le code postal de votre établissement',
                    'autocomplete' => 'off',
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
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom de la ville doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de la ville ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]+$/',
                        'message' => 'Le nom de la ville ne doit contenir que des lettres, espaces et tirets.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez la ville de votre établissement','autocomplete' => 'off',
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
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom de la région doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de la région ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\s-]+$/',
                        'message' => 'Le nom de la région ne doit contenir que des lettres, espaces et tirets.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez la région de votre établissement','autocomplete' => 'off',
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
                    'class' => 'form-label color-secondary-custom text-center'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100 color-primary-custom',
                    'data-loading-text' => 'Chargement...'
                ]
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
                        'class' => 'form-control',
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
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez fournir un mot de passe.'
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 30,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                            'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
                        ]),
                        new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
                    ])
                    ],
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Entrez votre mot de passe',
                        'autocomplete' => 'off'
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