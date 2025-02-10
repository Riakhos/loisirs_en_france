<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }
    
    /**
     * configureCrud
     *
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Tag')
            ->setEntityLabelInPlural('Tags')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('name', 'Nom')
                ->setHelp('Nom du tag')
                ->setColumns(6)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('activities', 'Activité associée')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setHelp('Sélectionnez les activités a associer.')
                ->setColumns(3)
            ,
            AssociationField::new('partners', 'Partenaire associée')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setHelp('Sélectionnez les partner a associer.')
                ->setColumns(3)
            ,
            AssociationField::new('events', 'Évènement spécial associée')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setHelp('Sélectionnez les évènements spéciaux a associer.')
                ->setColumns(3)
            ,
            AssociationField::new('offers', 'Offre spécial associée')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setHelp('Sélectionnez les offres spéciales a associer.')
                ->setColumns(3)
            ,
        ];
    }
}