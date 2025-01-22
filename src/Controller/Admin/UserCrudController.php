<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            // ->setDateFormat('...')
            // ...
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('email')->setLabel('Mail')->onlyOnIndex(),
            TextField::new('firstname')->setLabel('Prénom'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('birthdayDate')->setLabel('Date de naissance')->onlyOnIndex(),
            TextField::new('phone')->setLabel('Téléphone')->onlyOnIndex(),
            // TextEditorField::new('description'),
        ];
    }
}