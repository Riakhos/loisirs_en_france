<?php

namespace App\Controller\Account;

use App\Repository\RatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SearchRatingController extends AbstractController
{
    #[Route('/recherche-avis', name: 'app_search_rating', methods: ['GET'])]
    public function searchRating(Request $request, RatingRepository $ratingRepository): JsonResponse
    {
        // Pagination
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Nombre d'avis par page
        $offset = ($page - 1) * $limit;

        // Récupérer les filtres du formulaire
        $filters = $this->getFiltersFromRequest($request);

        // Création de la requête de recherche
        $queryBuilder = $ratingRepository->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Appliquer les filtres
        $this->applyFilters($queryBuilder, $filters);

        // Exécuter la requête
        $ratings = $queryBuilder->getQuery()->getResult();

        // Transformer les résultats en format JSON
        $data = $this->formatRatings($ratings);

        // Récupérer le nombre total d'avis pour la pagination
        $totalRatings = $ratingRepository->count($filters);
        $totalPages = ceil($totalRatings / $limit);

        // Retourner les résultats et les informations de pagination
        return new JsonResponse([
            'data' => $data,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    private function getFiltersFromRequest(Request $request): array
    {
        $filters = [];
        $params = ['score', 'date', 'activity', 'event', 'offer', 'partner', 'user'];

        foreach ($params as $param) {
            $value = $request->query->get($param);
            if ($value) {
                $filters[$param] = $value;
            }
        }

        return $filters;
    }

    private function applyFilters($queryBuilder, array $filters): void
    {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'score':
                    $queryBuilder->andWhere('r.score = :score')
                        ->setParameter('score', $value);
                    break;
                case 'date':
                    $queryBuilder->andWhere('r.createdAt >= :date')
                        ->setParameter('date', $value);
                    break;
                case 'activity':
                    $queryBuilder->andWhere('r.activity = :activity')
                        ->setParameter('activity', $value);
                    break;
                case 'event':
                    $queryBuilder->andWhere('r.event = :event')
                        ->setParameter('event', $value);
                    break;
                case 'offer':
                    $queryBuilder->andWhere('r.offer = :offer')
                        ->setParameter('offer', $value);
                    break;
                case 'partner':
                    $queryBuilder->andWhere('r.partner = :partner')
                        ->setParameter('partner', $value);
                    break;
                case 'user':
                    $queryBuilder->andWhere('r.user = :user')
                        ->setParameter('user', $value);
                    break;
            }
        }
    }

    private function formatRatings($ratings): array
    {
        return array_map(function ($rating) {
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
    }
}