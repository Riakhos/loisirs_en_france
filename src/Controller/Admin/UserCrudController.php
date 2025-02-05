<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
            TextField::new('email')
                ->setLabel('Mail')
                ->onlyOnIndex()
            ,
            TextField::new('firstname')
                ->setLabel('Prénom')
                ->setColumns(6)
            ,
            TextField::new('lastname')
                ->setLabel('Nom')
                ->setColumns(6)
            ,
            TextField::new('birthdayDate')
                ->setLabel('Date de naissance')
                ->onlyOnIndex()
                ->setColumns(6)
            ,
            TextField::new('phone')
                ->setLabel('Téléphone')
                ->onlyOnIndex()
                ->setColumns(6)
            ,
        ];
    }
}