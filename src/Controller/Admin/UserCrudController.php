<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('email', 'Mail')
                ->setHelp('Indiquer votre adresse mail')
                ->onlyOnIndex()
            ,
            TextField::new('firstname', 'Prénom')
                ->setHelp('Indiquer votre prénom')
                ->setColumns(6)
            ,
            TextField::new('lastname', 'Nom')
                ->setHelp('Indiquer votre nom')
                ->setColumns(6)
            ,
            DateField::new('birthdayDate', 'Date de naissance')
                ->setHelp('Indiquer votre date de naissance')
                ->onlyOnIndex()
                ->setColumns(6)
            ,
            TextField::new('phone', 'Téléphone')
                ->setHelp('Indiquer votre numéro de téléphone')
                ->onlyOnIndex()
                ->setColumns(6)
            ,
        ];
    }
}