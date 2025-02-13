<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un prénom'
                    ]),
                    new Length([
                        'min' =>3,
                        'max' => 15,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-ZÀ-ÿ][a-zà-ÿ '-]*$/",
                        'message' => "Ceci n'est pas un prénom valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre prénom',
                    'autocomplete' => 'on',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un nom'
                    ]),
                    new Length([
                        'min' =>3,
                        'max' => 25,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-ZÀ-ÿ][a-zà-ÿ '-]*$/",
                        'message' => "Ceci n'est pas un nom valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom',
                    'autocomplete' => 'on',
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir un numéro de téléphone'
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
                    'placeholder' => 'Votre numéro de téléphone',
                    'autocomplete' => 'on',
                ]
            ])
            ->add('birthdayDate', DateType::class, [
                'label' => 'Date de naissance <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir une date de naissance',
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) {
                        $today = new \DateTime(); 
                        $minDate = (clone $today)->modify('-18 years');

                        if ($value > $minDate) {
                            $context->buildViolation('Vous devez avoir plus de 18 ans.')
                                    ->atPath('birthdayDate')
                                    ->addViolation();
                        }
                    })
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'on',
                ]                
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail <span class="text-danger">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir une adresse mail.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email n\'est pas valide.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquez votre adresse mail',
                    'autocomplete' => 'on',
                ]
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Choisir votre photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'application/pdf',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (JPEG, PNG, PDF,GIF ou WEBP ).',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Le fichier doit être au format JPEG, PNG, PDF, GIF ou WEBP et ne doit pas dépasser 2 Mo.',
                'help_attr' => [
                    'class' => 'color-secondary-custom'
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
                'label' => 'Mettre à jour le profil',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-40',
                    'data-loading-text' => 'Chargement...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}