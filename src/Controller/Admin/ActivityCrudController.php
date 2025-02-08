<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ActivityCrudController extends AbstractCrudController
{
    /**
     * getEntityFqcn
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Activity::class;
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
            ->setEntityLabelInSingular('Activité')
            ->setEntityLabelInPlural('Activités')
        ;
    }
    
    /**
     * configureFields
     *
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        $required =true;
        
        if ($pageName == 'edit') {
            $required = false;
        }
        
        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('name')
                ->setLabel('Titre')
                ->setHelp('Titre de l\'activité')
                ->setColumns(6)
            ,
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre activité générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addFieldset('Images de l\'activité'),
            ImageField::new('image')
                ->setLabel('Image principale')
                ->setHelp('Image principale de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image1')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image2')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            ImageField::new('image3')
                ->setLabel('Image secondaire')
                ->setHelp('Image secondaire de votre activité en 600*600px')
                ->setUploadDir('/public/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setColumns(3)
            ,
            FormField::addFieldset('Tarification'),
            NumberField::new('price')
                ->setLabel('Prix T.T.C')
                ->setHelp('Prix T.T.C de l\activité sans le sigle Euro')
                ->setColumns(4)
            ,
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA')
                ->setChoices([
                    '5.5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20'
                ])
                ->setHelp('TVA de l\activité')
                ->setColumns(4)
            ,
            IntegerField::new('peopleCount')
                ->setLabel('Nombre de personnes')
                ->setHelp('Le nombre de personnes autorisées pour cette activité')
                ->setColumns(4)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('category', 'Catégories associées')->setColumns(6),
            AssociationField::new('subcategory', 'Sous-Catégories associées')->setColumns(6)
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp('Description de la sous-categorie')
        ];
    }
}