<?php

namespace App\Controller\Admin;

use App\Entity\Eventstrend;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventstrendCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Eventstrend::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Évènement Tendance')
            ->setEntityLabelInPlural('Évènements Tendances')
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
                ->setLabel('Titre')
                ->setHelp('Titre des évènements tendances')
                ->setColumns(6)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de vos évènements tendances générée automatiquement')
                ->setColumns(6)
            ,
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp('Image de votre évènement tendance en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setRequired($required)
            ,
            FormField::addPanel('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp("Description de l\'évènement spécial")
            ,
        ];
    }
    
}