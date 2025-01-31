<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Offre Spéciale')
            ->setEntityLabelInPlural('Offres Spéciales')
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
            FormField::addPanel('Informations principales'),
            TextField::new('name')
                ->setLabel('Nom')
                ->setHelp("Nom de l'offre spéciale")
                ->setColumns(6)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre offre spéciale générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addPanel('Image de l\'offre spéciale'),
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp("Image de votre offre spéciale en 600*600px")
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setRequired($required)
            ,
            FormField::addPanel('Tarification'),
            NumberField::new('price')
                ->setLabel('Prix H.T')
                ->setHelp("Prix H.T de l'offre spéciale sans le sigle Euro")
                ->setColumns(6)
            ,
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA')
                ->setChoices([
                    '5.5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20'
                ])
                ->setHelp("TVA de l'offre spéciale")
                ->setColumns(6)
            ,
            FormField::addPanel('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp("Description de l'offre spéciale")
            ,
            FormField::addPanel('Associations'),
            AssociationField::new('eventstrend', 'Évènements Tendances associées')
        ];
    }
}