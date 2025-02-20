<?php

namespace App\Controller\Account;

use App\Entity\Order;
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
    public function reservation(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer le numéro de page depuis l'URL (défaut : 1)
        $currentPage = max(1, $request->query->getInt('page', 1));
        $limit = 10; // Nombre de commandes par page
        
        // Récupérer l'état choisi dans l'URL
        $state = $request->query->getInt('state');

        // Construire le critère de recherche
        $criteria = ['user' => $user];
        if ($state !== null) {
            $criteria['state'] = $state;
        }
        
        // Compter le nombre total de commandes correspondant au filtre
        $totalOrders = $em->getRepository(Order::class)->count($criteria);
        $totalPages = ceil($totalOrders / $limit);
        
        // Récupérer les commandes avec pagination
        $orders = $em->getRepository(Order::class)->findBy(
            $criteria,
            ['createAt' => 'DESC'], 
            $limit, 
            ($currentPage - 1) * $limit
        );

        // Calculer le nombre de commandes par état
        $totalPending = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 0]);
        $totalValidated = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 1]);
        $totalShipped = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 2]);
        $totalCompleted = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 3]);
        
        return $this->render('account/reservation.html.twig', [
            'controller_name' => 'Vos Réservations',
            'orders' => $orders,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalOrders' =>$totalOrders,
            'state' => $state,
            'totalOrders' => $totalOrders,
            'totalPending' => $totalPending,
            'totalValidated' => $totalValidated,
            'totalShipped' => $totalShipped,
            'totalCompleted' => $totalCompleted,
        ]);
    }
}