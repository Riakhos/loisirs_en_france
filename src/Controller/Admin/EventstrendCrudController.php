<?php

namespace App\Controller\Admin;

use App\Entity\Eventstrend;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
            ->setEntityLabelInSingular('Eventstrend')
            ->setEntityLabelInPlural('Eventstrends')
            // ->setDateFormat('...')
            // ...
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {        
        return [
            TextField::new('name')
                ->setLabel('Titre')
                ->setHelp('Titre des évènements tendances')
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de vos évènements tendances générée automatiquement')
            ,
        ];
    }
    
}