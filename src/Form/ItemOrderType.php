<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ItemOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('itemId', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'display:none;'
                ]
            ])
            ->add('name', HiddenType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'display:none;'
                ]
            ])
            ->add('dateStart', DateType::class, [
                'label' => 'Choisissez la date de votre visite',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir une date.']),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être dans le futur.'
                    ])
                ]
            ])
            ->add('time', TimeType::class, [
                'label' => "Choisissez l'heure de votre visite si nécessaire",
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new Optional()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}