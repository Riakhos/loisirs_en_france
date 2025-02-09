<?php

namespace App\Controller\Admin;

use App\Entity\Exclusive;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ExclusiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Exclusive::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Offre Exclusive')
            ->setEntityLabelInPlural('Offres Exclusives')
            ->setDateFormat('dd/MM/yyyy')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required =true;
        
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('name')
                ->setLabel('Nom')
                ->setHelp("Nom de l'offre exclusive")
                ->setColumns(6)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre offre exclusive générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addFieldset('Image de l\'offre exclusive'),
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp("Image de votre offre exclusive en 600*600px")
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
            ,
            FormField::addFieldset('Tarification'),
            NumberField::new('discountPercentage')
                ->setLabel('Réduction (%)')
                ->setHelp("Pourcentage de réduction appliqué sur le prix de l'activité")
            ,
            FormField::addFieldset('Dates'),
            DateField::new('dateStart')
                ->setLabel('Date de début')
                ->setHelp("Date du début de l'offre exclusive")
                ->setColumns(6)
            ,
            DateField::new('dateStop')
                ->setLabel('Date de fin')
                ->setHelp("Date de la fin de l'offre exclusive")
                ->setColumns(6)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('activities', 'Activité associés')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                ])
                ->setHelp('Sélectionnez l\'activité sur laquelle s\'applique cette offre exclusive')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('attr', ['data-limit' => 1])
                ->setColumns(6)
            ,
            AssociationField::new('eventstrend', 'Évènements Tendances associés')
                ->setColumns(6)
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp("Description de l'offre exclusive")
        ];
    }
}