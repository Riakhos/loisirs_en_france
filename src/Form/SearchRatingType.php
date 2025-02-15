<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Rating;
use App\Entity\Partner;
use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchRatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score', ChoiceType::class, [
                'label' => 'Choisir une note',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    '★ et plus' => 1,
                    '★✩ et plus' => 1.5,
                    '★★ et plus' => 2,
                    '★★✩ et plus' => 2.5,
                    '★★★ et plus' => 3,
                    '★★★✩ et plus' => 3.5,
                    '★★★★ et plus' => 4,
                    '★★★★✩ et plus' => 4.5,
                    '★★★★★' => 5,
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ],
                'attr' => [
                    'class' => 'rating-choices d-flex flex-wrap justify-content-between color-secondary-custom mb-2'
                ],
            ])
            ->add('activity', EntityType::class, [
                'class' => Activity::class,
                'label' => 'Activité',
                'required' => false,
                'placeholder' => 'Choisir une activité',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'label' => 'Évènement spécial',
                'required' => false,
                'placeholder' => 'Choisir un événement spécial',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'label' => 'Offre spéciale',
                'required' => false,
                'placeholder' => 'Choisir une offre spéciale',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('partner', EntityType::class, [
                'class' => Partner::class,
                'label' => 'Partenaire',
                'required' => false,
                'placeholder' => 'Choisir un partenaire',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Utilisateur',
                'required' => false,
                'placeholder' => 'Choisir un utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('date', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'label' => 'Choisir un tri',
                'choices' => [
                    'Plus récent' => 'desc',
                    'Plus ancien' => 'asc',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ],
                'attr' => [
                    'class' => 'd-flex flex-column align-items-center'
                ]
            ])
            ->add('search', TextType::class, [
                'label' => 'Rechercher un avis',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Rechercher un avis...'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100 color-primary-custom',
                    'data-loading-text' => 'Chargement...'
                ]
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Réinitialiser',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-100 color-primary-custom'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}