<?php

namespace App\Controller\Account;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Rating;
use App\Entity\Activity;
use App\DTO\SearchRating;
use App\Form\SearchRatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchRatingController extends AbstractController
{
    #[Route('/recherche-avis', name: 'app_search_rating', methods: ['GET'])]
    public function searchRating(Request $request, EntityManagerInterface $em): Response
    {
        // Crée une instance de SearchRating (DTO)
        $searchRatingData = new SearchRating();
        
        $searchRatingForm = $this->createForm(SearchRatingType::class, $searchRatingData, [
            'method' => 'GET',
            'attr' => [
                'autocomplete' => 'off', // Ajout d'attribut global pour tout le formulaire
            ]
        ]);

        $searchRatingForm->handleRequest($request);

        // Vérification si un paramètre "activity" est passé dans l'URL
        $activityId = $request->query->get('activity');
        if ($activityId) {
            $searchRatingData->activity = $em->getRepository(Activity::class)->find($activityId);
        }
        
        // Vérification si un paramètre "offer" est passé dans l'URL
        $offerId = $request->query->get('offer');
        if ($offerId) {
            $searchRatingData->offer = $em->getRepository(Offer::class)->find($offerId);
        }
        
        // Vérification si un paramètre "event" est passé dans l'URL
        $eventId = $request->query->get('event');
        if ($eventId) {
            $searchRatingData->event = $em->getRepository(Event::class)->find($eventId);
        }

        // Si le formulaire est réinitialisé (reset), on réinitialise les filtres
        if ($searchRatingForm->isSubmitted() && $searchRatingForm->isValid()) {
            // Extraire les filtres si existants
            $searchRatingData = $searchRatingForm->getData();
        }
        
        // Récupérer tous les avis
        $ratingRepository = $em->getRepository(Rating::class);

        // Construire la requête avec filtres dynamiques
        $queryBuilder = $ratingRepository->createQueryBuilder('r');

        if (!empty($searchRatingData)) {
    
            if ($searchRatingData->score) {
                $queryBuilder
                    ->andWhere('r.score >= :score')
                    ->setParameter('score', $searchRatingData->score);
            }
            if ($searchRatingData->activity) {
                $queryBuilder
                    ->andWhere('r.activity = :activity')
                    ->setParameter('activity', $searchRatingData->activity);
            }
            if ($searchRatingData->event) {
                $queryBuilder
                    ->andWhere('r.event = :event')
                    ->setParameter('event', $searchRatingData->event);
            }
            if ($searchRatingData->offer) {
                $queryBuilder
                    ->andWhere('r.offer = :offer')
                    ->setParameter('offer', $searchRatingData->offer);
            }
            if ($searchRatingData->partner) {
                $queryBuilder
                    ->andWhere('r.partner = :partner')
                    ->setParameter('partner', $searchRatingData->partner);
            }
            if ($searchRatingData->user) {
                $queryBuilder
                    ->andWhere('r.user = :user')
                    ->setParameter('user', $searchRatingData->user);
            }
            if ($searchRatingData->createdAt) {
                $queryBuilder->orderBy('r.createdAt', $searchRatingData->createdAt);
            }
            if ($searchRatingData->search) {
                $queryBuilder
                    ->andWhere('r.comment LIKE :search')
                    ->setParameter('search', '%' . $searchRatingData->search . '%');
            }
        }

        // Tri des avis par date (du plus récent au plus ancien)
        $queryBuilder->orderBy('r.createdAt', 'DESC');

        // Récupération du numéro de page (par défaut 1)
        $currentPage = max(1, $request->query->getInt('page', 1));
        $limit = 10; // Nombre d'avis par page
        
        // Compter le nombre total d'avis
        $totalRatings = $ratingRepository->count([]);
        
        // Calcul du nombre total de pages
        $totalPages = ceil($totalRatings / $limit);

        // Récupérer les avis paginés
        $ratings = $queryBuilder
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        
        // Préparer les données des avis
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
        
        return $this->render('account/search_results.html.twig', [
            'controller_name' => 'Tous Vos Avis',
            'ratingsData' => $ratingsData,
            'searchRatingForm' => $searchRatingForm->createView(),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]); 
    }
}   