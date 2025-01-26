<?php

namespace App\Controller\Admin;

use App\Entity\Subcategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubcategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subcategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sous-Catégorie')
            ->setEntityLabelInPlural('Sous-Catégories')
            // ->setDateFormat('...')
            // ...
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required =true;
        
        if ($pageName == 'edit') {
            $required = false;
        }
        
        return [
            TextField::new('name')
                ->setLabel('Nom')
                ->setHelp('Nom de la sous-catégorie')
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre sous-catégorie générée automatiquement')
            ,
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp('Description de la sous-categorie')
            ,
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp('Image de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setRequired($required)
            ,
            AssociationField::new('category', 'Catégories associées'),
        ];
    }
}