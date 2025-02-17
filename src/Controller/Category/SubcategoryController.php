<?php

namespace App\Controller\Category;

use App\Entity\Rating;
use App\Form\RatingType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubcategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubcategoryController extends AbstractController
{
    #[Route('/sous-categorie/{slug}', name: 'app_subcategory')]
    public function subcategory(string $slug, SubcategoryRepository $subcategoryRepository, EntityManagerInterface $em, Request $request): Response
    {        
        // Trouver la sous-catégorie par son slug
        $subcategory = $subcategoryRepository->findOneBySlug($slug);

        // Si la sous-catégorie n'existe pas, redirigez vers la page d'accueil ou affichez une erreur
        if (!$subcategory) {
            $this->addFlash(
                'error', 
                "La sous-catégorie demandée n'existe pas."
            );
            return $this->redirectToRoute('app_home');
        }

        // Créer un tableau pour stocker les formulaires de notation
        $ratingForms = [];
        
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();

        // L'utilisateur est connecté, on génère les formulaires
        if ($user) {
            foreach ($subcategory->getActivities() as $activity) {
                // Créer un formulaire de notation pour chaque activité
                $rating = new Rating();
                // Lier la note à l'activité correspondante
                $rating->setActivity($activity);
                $rating->setUser($user);
                
                $form = $this->createForm(RatingType::class, $rating);
                $form->handleRequest($request);
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($rating);
                    $em->flush();
    
                    $this->addFlash(
                        'success',
                        'Votre note a été ajoutée avec succès.'
                    );
                    
                    // Rediriger vers la même page pour éviter un double envoi du formulaire
                    return $this->redirectToRoute('app_subcategory', [
                        'slug' => $subcategory->getSlug()
                    ]);
                }
    
                // Ajouter le formulaire de chaque activité au tableau
                $ratingForms[$activity->getId()] = $form->createView();
            }
        }
        
        return $this->render('category/subcategory.html.twig', [
            'controller_name' => 'Sous-Catégorie',
            'subcategory' => $subcategory,
            'ratingForms' => $ratingForms,
        ]);
    }
}