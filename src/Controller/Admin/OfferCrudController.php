<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Count;
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

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Offre Spéciale')
            ->setEntityLabelInPlural('Offres Spéciales')
            ->setDateFormat('dd/MM/yyyy')
        ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Offer) {
            if ($entityInstance->getActivity()->count() > 3) {
                throw new \Exception("Vous ne pouvez associer que trois activités maximum.");
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        $required =true;
        
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            FormField::addFieldset('Informations principales'),
            TextField::new('name', 'Nom')
                ->setHelp("Nom de l'offre spéciale")
                ->setColumns(6)
            ,
            SlugField::new('slug', 'URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre offre spéciale générée automatiquement')
                ->setColumns(6)
            ,
            FormField::addFieldset('Image de l\'offre spéciale'),
            ImageField::new('image', 'Image')
                ->setHelp("Image de votre offre spéciale en 600*600px")
                ->setUploadDir('/public/uploads/photos')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].webp')
                ->setBasePath('/uploads/photos')
                ->setRequired($required)
            ,
            FormField::addFieldset('Tarification'),
            NumberField::new('price', 'Prix T.T.C')
                ->setHelp("Prix T.T.C de l'offre spéciale sans le sigle Euro")
                ->setColumns(4)
            ,
            ChoiceField::new('tva', 'Taux de TVA')
            ->setLabel('Taux de TVA')
                ->setChoices([
                    '5.5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20'
                ])
                ->setHelp("TVA de l'offre spéciale")
                ->setColumns(4)
            ,
            IntegerField::new('peopleCount')
                ->setLabel('Nombre de personnes')
                ->setHelp('Le nombre de personnes autorisées pour cet évènement spécial')
                ->setColumns(4)
            ,
            FormField::addFieldset('Associations'),
            AssociationField::new('eventstrend', 'Évènements Tendances associées')
                ->setHelp('L\évènement tendance de l\offre spéciale')
                ->setColumns(4)
            ,
            // On utilise un champ personnalisé pour sélectionner 3 activités
            AssociationField::new('activity', 'Activité associée')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    },
                ])
                ->setHelp('Sélectionnez jusqu\'à 3 activités.')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('attr', ['data-limit' => 3])
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('constraints', [
                    new Count(
                        max: 3,
                        maxMessage: "Vous ne pouvez sélectionner que trois activités maximum."
                    )
                ])
                ->setColumns(4)
            ,
            AssociationField::new('tags', 'Tags associés')
                ->setHelp('Sélectionnez ou ajoutez des tags pour cette offre spéciale')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->setCrudController(TagCrudController::class)
                ->setColumns(4)
            ,
            FormField::addFieldset('Description'),
            TextEditorField::new('description', 'Description')
                ->setHelp("Description de l'offre spéciale")  
        ];
    }
}