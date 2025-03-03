<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAllType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder 
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'label' => 'Nom de l\'offre spéciale',
                'required' => false,
                'placeholder' => 'Choisir une offre spéciale',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder' => 'Rechercher dans Offre spéciale'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'label' => 'Nom de l\'évènement spécial',
                'required' => false,
                'placeholder' => 'Choisir un évènement spécial',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder' => 'Rechercher dans Évènement spécial'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('activity', EntityType::class, [
                'class' => Activity::class,
                'label' => 'Nom de l\'Activité',
                'required' => false,
                'placeholder' => 'Choisir une activité',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder' => 'Rechercher dans Activité'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'label' => 'Tranche d\'âge',
                'required' => false,
                'placeholder' => 'Choisir un tag',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder' => 'Rechercher dans Tag'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('region', TextType::class, [
                'label' => 'Région',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' =>3,
                        'max' => 50,
                        'minMessage' => 'La région doit doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La région ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-zÀ-ÖØ-öø-ÿ0-9'&.-]{2,50}$/",
                        'message' => "Ceci n'est pas une région valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez une région',
                    'autocomplete' => 'off'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' =>2,
                        'max' => 50,
                        'minMessage' => 'La ville doit doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La ville ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-zÀ-ÖØ-öø-ÿ0-9'&.-]{2,50}$/",
                        'message' => "Ceci n'est pas une région valide"
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez une ville',
                    'autocomplete' => 'off'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            // Recherche par distance (rayon en km par rapport à Partner.address)
            ->add('distance', RangeType::class, [
                'label' => 'Rayon de recherche (km)',
                'required' => false,
                'attr' => [
                    'class' => 'form-range',
                    'min' => 0,
                    'max' => 200,
                    'step' => 1,
                    'value' => 100, // Valeur par défaut au milieu de l'échelle
                    'oninput' => 'document.getElementById("distanceValue").innerText = this.value + " Km";',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom',
                ]
            ])
            ->add('price', RangeType::class, [
                'label' => 'Prix (en €)',
                'required' => false,
                'attr' => [
                    'class' => 'form-range',
                    'min' => 0,
                    'max' => 200,
                    'step' => 1,
                    'value' => 100, // Valeur par défaut au milieu de l'échelle
                    'oninput' => 'document.getElementById("priceValue").innerText = this.value + " €";',
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom',
                ]
            ])
            ->add('score', ChoiceType::class, [
                'label' => 'Note minimale',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    '★ et plus' => 1,
                    '★★ et plus' => 2,
                    '★★★ et plus' => 3,
                    '★★★★ et plus' => 4,
                    '★★★★★' => 5,
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ],
                'attr' => [
                    'class' => 'rating-choices d-flex flex-wrap justify-content-between color-secondary-custom mb-2',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
                'label' => 'Pseudo',
                'required' => false,
                'placeholder' => 'Choisir un pseudo',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ],
                'label_attr' => [
                    'class' => 'form-label color-secondary-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-75',
                    'data-loading-text' => 'Chargement...'
                ],
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Réinitialiser',
                'attr' => [
                    'class' => 'btn btn-primary d-block mx-auto w-75 color-primary-custom',
                    'id' => 'reset-button', // Ajout de l'ID pour le script JS
                    'autocomplete' => 'off'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',  // Nous utilisons GET pour la recherche
        ]);
    }
}