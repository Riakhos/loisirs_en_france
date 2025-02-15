<?php

namespace App\Controller\Account;

use App\Repository\RatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SearchRatingController extends AbstractController
{
    #[Route('/recherche-avis', name: 'app_search_rating', methods: ['GET'])]
    public function searchRating(Request $request, RatingRepository $ratingRepository): JsonResponse
    {
        // Récupérer les paramètres de recherche
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Nombre d'avis par page
        $offset = ($page - 1) * $limit;

        // Récupération des avis (ajuste cette requête selon tes besoins)
        // $ratings = $ratingRepository->findBy([], ['createdAt' => 'DESC'], $limit, $offset);
        
        // Récupérer les filtres du formulaire
        $filters = [];
        $score = $request->query->get('score');
        $date = $request->query->get('date');
        $activity = $request->query->get('activity');
        $event = $request->query->get('event');
        $offer = $request->query->get('offer');
        $partner = $request->query->get('partner');
        $user = $request->query->get('user');

        // Ajouter les filtres si présents
        if ($score) {
            $filters['score'] = $score;
        }
        if ($date) {
            $filters['createdAt'] = $date;
        }
        if ($activity) {
            $filters['activity'] = $activity;
        }
        if ($event) {
            $filters['event'] = $event;
        }
        if ($offer) {
            $filters['offer'] = $offer;
        }
        if ($partner) {
            $filters['partner'] = $partner;
        }
        if ($user) {
            $filters['user'] = $user;
        }

        // Appliquer les filtres à la recherche des avis
        $queryBuilder = $ratingRepository->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Appliquer les filtres dynamiquement
        foreach ($filters as $key => $value) {
            if ($key === 'score') {
                $queryBuilder->andWhere('r.score = :score')
                                ->setParameter('score', $value);
            } elseif ($key === 'createdAt') {
                $queryBuilder->andWhere('r.createdAt >= :date')
                                ->setParameter('date', $value);
            } elseif ($key === 'activity') {
                $queryBuilder->andWhere('r.activity = :activity')
                                ->setParameter('activity', $value);
            } elseif ($key === 'event') {
                $queryBuilder->andWhere('r.event = :event')
                                ->setParameter('event', $value);
            } elseif ($key === 'offer') {
                $queryBuilder->andWhere('r.offer = :offer')
                                ->setParameter('offer', $value);
            } elseif ($key === 'partner') {
                $queryBuilder->andWhere('r.partner = :partner')
                                ->setParameter('partner', $value);
            } elseif ($key === 'user') {
                $queryBuilder->andWhere('r.user = :user')
                                ->setParameter('user', $value);
            }
        }
        
        // Exécuter la requête
        $ratings = $queryBuilder->getQuery()->getResult();
        
        // Transformer les avis en JSON
        $data = array_map(function ($rating) {
            return [
                'score' => $rating->getScore(),
                'comment' => $rating->getComment(),
                'user' => [
                    'email' => $rating->getUser() ? $rating->getUser()->getEmail() : 'Utilisateur inconnu',
                ],
                'createdAt' => $rating->getCreatedAt()->format('Y-m-d H:i:s'),
                'partner' => $rating->getPartner() ? $rating->getPartner()->getName() : 'Inconnu',
                'activity' => $rating->getActivity() ? $rating->getActivity()->getName() : 'Inconnue',
                'event' => $rating->getEvent() ? $rating->getEvent()->getName() : 'Inconnue',
                'offer' => $rating->getOffer() ? $rating->getOffer()->getName() : 'Inconnue',
            ];
        }, $ratings);

        return new JsonResponse($data);
    }
}