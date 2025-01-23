<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ActivityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Activity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Activité')
            ->setEntityLabelInPlural('Activités')
            // ->setDateFormat('...')
            // ...
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Titre')->setHelp('Titre de l\'activité'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('URL de votre activité générée automatiquement'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description de la sous-categorie'),
            ImageField::new('image')->setLabel('Image principale')->setHelp('Image principale de votre activité en 600*600px')->setUploadDir('/public/uploads')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads'),
            ImageField::new('image1')->setLabel('Image secondaire')->setHelp('Image secondaire de votre activité en 600*600px')->setUploadDir('/public/uploads')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads'),            
            ImageField::new('image2')->setLabel('Image secondaire')->setHelp('Image secondaire de votre activité en 600*600px')->setUploadDir('/public/uploads')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads'),            
            ImageField::new('image3')->setLabel('Image secondaire')->setHelp('Image secondaire de votre activité en 600*600px')->setUploadDir('/public/uploads')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads'),            
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Prix H.T de l\activité sans le sigle Euro'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '5.5%' => '5.5',
                '10%' => '10',
                '20%' => '20'
            ]),
            AssociationField::new('category', 'Catégories associées'),
            AssociationField::new('subcategory', 'Sous-Catégories associées'),
        ];
    }
}