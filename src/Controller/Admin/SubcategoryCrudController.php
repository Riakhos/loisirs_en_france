<?php

namespace App\Controller\Admin;

use App\Entity\Subcategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
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
            TextField::new('name', 'Nom')
                ->setHelp('Nom de la sous-catégorie')
                ->setColumns(6)
            ,
            SlugField::new('slug', 'URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre sous-catégorie générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addFieldset('Image de la sous-catégorie'),
            ImageField::new('image', 'Image')
                ->setHelp('Image de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(6)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('category', 'Catégories associées')
                ->setHelp('La catégorie a associer')
                ->setColumns(6)
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description', 'Description')
                ->setHelp('Description de la sous-categorie')
        ];
    }
}