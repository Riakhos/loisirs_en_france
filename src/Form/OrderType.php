<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => ItemOrderType::class,
                'allow_add' => false, 
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer la commande',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-40'
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