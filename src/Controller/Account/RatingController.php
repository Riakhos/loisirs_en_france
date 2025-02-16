<?php

namespace App\Controller\Account;

use App\Entity\Rating;
use App\Form\SearchRatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{	
    #[Route('/compte/avis', name: 'app_account_rating')]
    public function rating(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $searchRatingForm = $this->createForm(SearchRatingType::class, null, [
            'attr' => [
                'method' => 'GET',
                'autocomplete' => 'off', // Ajout d'attribut global pour tout le formulaire
            ]
        ]);
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération des informations pour la pagination
        $page = $request->query->getInt('page', 1); // Page actuelle (1 par défaut)
        $limit = 10; // Nombre d'éléments par page

        // Compter le nombre total d'avis
        $totalRatings = $em->getRepository(Rating::class)->count(['user' => $user]);

        // Calculer le nombre total de pages
        $totalPages = ceil($totalRatings / $limit);

        // Récupérer les avis pour la page actuelle
        $ratings = $em->getRepository(Rating::class)->findBy(
            ['user' => $user],
            null,
            $limit, // Limit
            ($page - 1) * $limit // Décalage pour la page
        );

        // Préparer les données avec calcul des étoiles
        $ratingsData = array_map(function (Rating $rating) {
            $score = $rating->getScore();
            return [
                'rating' => $rating,
                'fullStars' => floor($score), // Arrondi inférieur (ex: 4.5 -> 4)
                'hasHalfStar' => ($score - floor($score)) === 0.5, // Vérifie si on a exactement 0.5
                'emptyStars' => 5 - floor($score) - (($score - floor($score)) === 0.5 ? 1 : 0), // Complète jusqu'à 5 étoiles
                'partner' => $rating->getPartner(),
                'loisir' => $rating->getActivity()?->getName() ?? 
                            $rating->getEvent()?->getName() ?? 
                            $rating->getOffer()?->getName() ?? 'Non spécifié'
            ];
        }, $ratings);
            
        return $this->render('account/rating.html.twig', [
            'controller_name' => 'Vos Avis',
            'ratingsData' => $ratingsData,
            'user' => $user,
            'searchRatingForm' => $searchRatingForm->createView(),
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
    }
}