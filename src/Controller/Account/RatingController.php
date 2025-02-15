<?php

namespace App\Controller\Account;

use App\Entity\Rating;
use App\Form\SearchRatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{	
    #[Route('/compte/avis', name: 'app_account_rating')]
    public function rating(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $searchRatingForm = $this->createForm(SearchRatingType::class);
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Récupérer les avis de l'utilisateur connecté
        $ratings = $em->getRepository(Rating::class)->findBy([
            'user' => $user,
        ]);

        // Préparer les données avec calcul des étoiles
        $ratingsData = [];
        foreach ($ratings as $rating) {
            $score = $rating->getScore();
            $fullStars = floor($score); // Arrondi inférieur (ex: 4.5 -> 4)
            $hasHalfStar = ($score - $fullStars) === 0.5; // Vérifie si on a exactement 0.5
            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0); // Complète jusqu'à 5 étoiles
            
            // Assurez-vous que emptyStars n'est pas négatif (ce qui ne devrait pas arriver)
            if ($emptyStars < 0) {
                $emptyStars = 0;
            }
            
            // Ajouter un tableau associatif avec les données nécessaires
            $ratingsData[] = [
                'rating' => $rating,
                'fullStars' => $fullStars,
                'hasHalfStar' => $hasHalfStar,
                'emptyStars' => $emptyStars
            ];
        }
            
        return $this->render('account/rating.html.twig', [
            'controller_name' => 'Vos Avis',
            'ratingsData' => $ratingsData,
            'user' => $user,
            'searchRatingForm' => $searchRatingForm->createView(),
        ]);
    }
}