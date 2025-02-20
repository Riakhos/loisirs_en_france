<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rating::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Note et avis')
            ->setEntityLabelInPlural('Notes et avis')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            NumberField::new('score', 'Note')
                ->setHelp('Score attribué par l\'utilisateur')
                ->setColumns(6)
            ,
            TextField::new('comment', 'Commentaire')
                ->setHelp('Commentaire laissé par l\'utilisateur')
                ->setColumns(6)
            ,
            DateTimeField::new('createdAt', 'Date de création')
                ->setHelp('Date à laquelle l\'évaluation a été créée')
                ->setColumns(6)
                ->setDisabled()
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('activity', 'Activité évaluée')
                ->setHelp('Sélectionnez l\'activité associée à cette évaluation.')
                ->setColumns(3)
            ,
            AssociationField::new('event', 'Événement évalué')
                ->setHelp('Sélectionnez l\'événement associé à cette évaluation.')
                ->setColumns(3)
            ,
            AssociationField::new('offer', 'Offre évaluée')
                ->setHelp('Sélectionnez l\'offre associée à cette évaluation.')
                ->setColumns(3)
            ,
            AssociationField::new('partner', 'Partenaire évalué')
                ->setHelp('Sélectionnez le partenaire associé à cette évaluation.')
                ->setColumns(3)
            ,
        ];
    }
}