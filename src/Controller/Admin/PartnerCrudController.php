<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PartnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Partenaire')
            ->setEntityLabelInPlural('Partenaires')
            ->setDateFormat('dd/MM/yyyy')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $created_atField = DateTimeField::new('created_at')
            ->setLabel('Date de création')
            ->setHelp('Cette date est définie automatiquement')
            ->setRequired(false)
            ->setFormTypeOption('disabled', true)
        ;
    
        if ($pageName === Crud::PAGE_NEW) {
            $created_atField->setValue(new \DateTimeImmutable());
        }

         // Liste des régions de France
        $regions = [
            'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
            'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
            'Bretagne' => 'Bretagne',
            'Centre-Val de Loire' => 'Centre-Val de Loire',
            'Corse' => 'Corse',
            'Grand Est' => 'Grand Est',
            'Hauts-de-France' => 'Hauts-de-France',
            'Île-de-France' => 'Île-de-France',
            'Normandie' => 'Normandie',
            'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
            'Occitanie' => 'Occitanie',
            'Pays de la Loire' => 'Pays de la Loire',
            'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
        ];
        
        return[
            FormField::addPanel('Informations principales'),
            TextField::new('name', 'Nom')
                ->setHelp('Nom du partenaire')
                ->setColumns(6)
            ,
            SlugField::new('slug', 'URL')
                ->setTargetFieldName('name')
                ->setHelp('URL du partenaire générée automatiquement')
                ->setColumns(6)
            ,
            TextField::new('address', 'Adresse')
                ->setHelp('Renseigner l\'adresse complète du partenaire')
                ->setColumns(12)
            ,
            TextField::new('postal', 'Code Postal')
                ->setHelp('Indiquer votre code postal')
                ->setColumns(6)
            ,
            TextField::new('city', 'Ville')
                ->setHelp('Indiquer votre ville')
                ->setColumns(6)
            ,
            ChoiceField::new('region', 'Région')
                ->setChoices($regions)
                ->setHelp('Sélectionner la région en France')
                ->setColumns(6)
            ,
            TextField::new('phone', 'Téléphone')
                ->setHelp('Indiquer votre numéro de téléphone')
                ->setColumns(6)
            ,
            EmailField::new('email', 'Email')
                ->setHelp('Indiquer votre adresse mail')
                ->setColumns(6)
            ,
            TextField::new('website', 'Site web')
                ->setHelp('Indiquer votre site web(Optionnel')
                ->setColumns(6)
                ->hideOnIndex()
            ,
            FormField::addPanel('Relations'),
            AssociationField::new('activities', 'Activités associées')
                ->setHelp("Sélectionnez les activités que l'on souhaite associer")
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                ])
                ->setFormTypeOption('choice_label', 'name')
                ->setColumns(3)
            , 
            AssociationField::new('events', 'Événements spéciaux associées')
                ->setHelp("Sélectionnez les événements spéciaux que l'on souhaite associer")
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                ])
                ->setFormTypeOption('choice_label', 'name')
                ->setColumns(3)
            ,
            AssociationField::new('offers', 'Offres spéciales associées')
                ->setHelp("Sélectionnez les offres spéciales que l'on souhaite associer")
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                ])
                ->setFormTypeOption('choice_label', 'name')
                ->setColumns(3)
            ,
            AssociationField::new('tags', 'Tags associés')
                ->setHelp('Sélectionnez ou ajoutez des tags pour ce partenaire')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setCrudController(TagCrudController::class)
                ->setColumns(3)
            ,
            FormField::addFieldset('Présentation'),
            TextEditorField::new('presentation', 'Présentation')
                ->setHelp("Présentation du partenaire")
                ->setColumns(6)
            ,
            $created_atField
                ->setColumns(6)
        ];
    }
}