<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['createAt' => 'DESC']);
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');
        
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function show(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        return $this->render('admin/reservation.html.twig', [
            'order' => $order
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations principales'),
            IdField::new('id', 'Numéro commande')
                ->setColumns(6)
            ,
            DateField::new('createAt', 'Date de création')
                ->setColumns(6)
            ,
            NumberField::new('state', 'Statut')
                ->setTemplatePath('admin/status.html.twig')
                ->setColumns(6)
            ,
            FormField::addPanel('Informations du client'),
            AssociationField::new('user', 'Utilisateur')
                ->setColumns(6)
            ,
            FormField::addPanel('Détails de la commande'),
            TextField::new('partnerName', 'Partenaire')
                ->setColumns(6)
            ,
            TextField::new('activityName', 'Activité')
                ->setColumns(6)
            ,
            TextField::new('offerName', 'Offre')
                ->setColumns(6)
            ,
            TextField::new('exclusiveName', 'Exclusivité')
                ->setColumns(6)
            ,
            TextField::new('eventName', 'Événement')
                ->setColumns(6)
            ,
            NumberField::new('cartPrice', 'Prix total')
                ->setNumDecimals(2) // Force l'affichage de 2 décimales
                ->setColumns(6)
            ,
        ];
    }
}