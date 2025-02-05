<?php

namespace App\Controller\Admin;

use App\Entity\Trend;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TrendCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trend::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Activité Tendance')
            ->setEntityLabelInPlural('Activités Tendances')
            ->setDateFormat('dd/MM/yyyy')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required =true;
        
        if ($pageName == 'edit') {
            $required = false;
        }

        $dateField = DateField::new('date')
        ->setLabel('Date de création')
        ->setHelp('Cette date est définie automatiquement')
        ->setRequired(false);
    
    if ($pageName === Crud::PAGE_NEW) {
        $dateField->setValue(new \DateTimeImmutable());
    }
        
        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('name')
                ->setLabel('Nom')
                ->setHelp('Nom de l\'activité tendance')
                ->setColumns(4)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre activité tendance générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addFieldset('Image de l\'activité tendance'),
            ImageField::new('image')
                ->setLabel('Image')
                ->setHelp('Image de votre activité tendance en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('eventstrend', 'Évènements Tendances associées')
                ->setColumns(6)
            ,
            AssociationField::new('activity', 'Activités')
                ->setColumns(6)
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp("Description de l\'évènement spécial")
                ->setColumns(6)
            ,
            $dateField
                ->setColumns(6)
        ];
    }
}