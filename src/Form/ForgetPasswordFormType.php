<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ForgetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Votre adresse e-mail <span class="text-danger">*</span>',
            'label_html' => true,
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez fournir une adresse mail.'
                ]),
                new Email([
                    'message' => 'Adresse email invalide.'
                ])
            ],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Indiquez votre adresse mail'
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
            'label' => 'Envoyer le lien de réinitialisation',
            'attr' => [
                'class' => 'btn btn-primary  d-block mx-auto w-40',
                'data-loading-text' => 'Chargement...'
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}