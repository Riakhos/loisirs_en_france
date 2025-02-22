<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Repository\OrderRepository;
use App\Repository\RatingRepository;
use App\Repository\PartnerRepository;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * index
     *
     * @return Response
     */
    #[Route('/compte', name: 'app_account')]
    public function index(
        RatingRepository $ratingRepository,
        PartnerRepository $partnerRepository,
        Cart $cartService,
        OrderRepository $orderRepository
    ): Response
    {
        $user = $this->getUser();

         // Récupérer les 5 dernières commandes avec l'état '3' (loisirs effectués)
        $recentActivities = $orderRepository->findBy(
            [
                'user' => $user,
                'state' => 3
            ],
            ['createAt' => 'DESC'], // Trier par date de création (récente en premier)
            5 // Limiter les résultats à 5
        );

        // Récupérer les réservations à venir avec l'état '2'
        $upcomingReservations = $orderRepository->findBy(
            ['user' => $user, 'state' => 2],
            ['createAt' => 'DESC']
        );
        
        return $this->render('account/index.html.twig', [
            'controller_name' => 'Tableau de bord',
            'user' => $user,
            'upcomingReservations' => $upcomingReservations,
            'ratings' => $ratingRepository->findBy([
                'user' => $user
            ]),
            'cartItems' => $cartService->getCart(),
            'partners' => $partnerRepository->findAll(),
            'recentActivities' => $recentActivities
        ]);
    }
}