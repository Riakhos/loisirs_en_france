<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAuthenticated = $options['is_authenticated']; // Récupération de l'option personnalisée
        
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre article <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un titre'
                    ]),
                    new Length([
                        'min' =>5,
                        'max' => 50,
                        'minMessage' => 'Le titre de l\'article doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre de l\'article ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9.,!?\'"\s-]+$/u',
                        'message' => "Ceci n'est pas un titre valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez le titre de votre article',
                    'autocomplete' => 'off'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire votre article.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 500,
                        'minMessage' => 'Votre article doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre article ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9.,!?\'"\s-]+$/u',
                        'message' => 'L\' article contient des caractères non autorisés.'
                    ]),
                ],
                'attr' => [
                    'maxlength' => 250,
                    'class' => 'form-control',
                    'placeholder' => 'Écrivez votre article',
                    'autocomplete' => 'off',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Choisir une image pour votre article',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (JPEG, PNG, GIF ou WEBP ).',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control color-primary-custom',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('blogueur_pseudo', TextType::class, [
                'label' => 'Pseudo de l\'utilisateur',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un pseudo.']),
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9.,!?\'"\s-]+$/u',
                        'message' => 'Le pseudo contient des caractères non autorisés.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control color-secondary-custom',
                    'placeholder' => 'Entrez votre pseudo'
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
                'label' => 'Publier',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-75 color-primary-custom',
                    'data-loading-text' => 'Chargement...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'is_authenticated' => false,
        ]);
    }
}