<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Évènement spéciale')
            ->setEntityLabelInPlural('Évènements spéciaux')
            ->setDateFormat('dd/MM/yyyy');
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
                ->setHelp("Nom de l\'évènement spécial")
                ->setColumns(6)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp("URL de votre évènement spéciale générée automatiquement")
                ->setColumns(6)
            ,
            FormField::addFieldset('Images de l\'évènement spéciale'),
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp("Image de votre évènement spéciale en 600*600px")
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image1')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image2')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image3')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            FormField::addFieldset('Tarification'),
            NumberField::new('price')
                ->setLabel('Prix T.T.C')
                ->setHelp("Prix T.T.C de l\'évènement spéciale sans le sigle Euro")
                ->setColumns(6)
            ,
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA')
                ->setChoices([
                    '5.5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20'
                ])
                ->setHelp("TVA de l'évènement spéciale")
                ->setColumns(6)
            ,
            FormField::addFieldset('Dates'),
            DateField::new('dateStart')
                ->setLabel('Date de début')
                ->setHelp("Date du début de l\'évènement spécial")
                ->setColumns(6)
            ,
            DateField::new('dateStop')
                ->setLabel('Date de fin')
                ->setHelp("Date de la fin de l\'évènement spécial")
                ->setColumns(6)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('eventstrend', 'Évènements Tendances associées')
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp("Description de l\'évènement spécial")
        ];
    }
}