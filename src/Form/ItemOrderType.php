<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
                'label' => 'Date',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir une date.'])
                ]
            ])
            ->add('time', TimeType::class, [
                'label' => 'Heure',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir une heure.'])
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