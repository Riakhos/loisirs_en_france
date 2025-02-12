<?php

namespace App\Controller\Account;

use App\Classe\Cart;
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
        // ReservationRepository $reservationRepository, 
        // MessageRepository $messageRepository, 
        RatingRepository $ratingRepository,
        PartnerRepository $partnerRepository,
        ActivityRepository $activityRepository,
        Cart $cartService
    ): Response
    {
        $user = $this->getUser();
        
        return $this->render('account/index.html.twig', [
            'controller_name' => 'Tableau de bord',
            'user' => $user,
            // 'upcomingReservations' => $reservationRepository->findUpcomingByUser($user),
            // 'ratings' => $ratingRepository->findBy([
            //     'user' => $user
            // ]),
            'cartItems' => $cartService->getCart(),
            'partners' => $partnerRepository->findAll(),
            // 'recentActivities' => $activityRepository->findRecentByUser($user),
        ]);
    }
}