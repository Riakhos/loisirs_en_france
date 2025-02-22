<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    #[Route('/compte/reservation', name: 'app_account_reservation')]
    public function reservation(Request $request, OrderRepository $orderRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer le numéro de page depuis l'URL (défaut : 1)
        $currentPage = max(1, $request->query->getInt('page', 1));
        $limit = 10; // Nombre de commandes par page

        // Récupérer l'état depuis l'URL (optionnel)
        $state = $request->query->getInt('state');

        // Compter le nombre total de commandes correspondant au filtre
        $totalOrders = $orderRepository->countOrdersByUser($user, $state);
        $totalPages = ceil($totalOrders / $limit);

        // Récupérer les commandes paginées
        $orders = $orderRepository->findOrdersByUser($user, $state, $limit, ($currentPage - 1) * $limit);

        // Calculer le nombre de commandes par état
        $totalPending = $orderRepository->countOrdersByState($user, 1);
        $totalValidated = $orderRepository->countOrdersByState($user, 2);
        $totalShipped = $orderRepository->countOrdersByState($user, 3);
        $totalCompleted = $orderRepository->countOrdersByState($user, 4);

        return $this->render('account/reservation.html.twig', [
            'controller_name' => 'Vos Réservations',
            'orders' => $orders,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders,
            'state' => $state,
            'totalPending' => $totalPending,
            'totalValidated' => $totalValidated,
            'totalShipped' => $totalShipped,
            'totalCompleted' => $totalCompleted,
        ]);
    }
}