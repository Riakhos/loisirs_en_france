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
        
        // Récupérer l'état choisi dans l'URL (par défaut toutes les commandes)
        $state = $request->query->getInt('state', 1);

        // Définir la date du jour à minuit (00:00:00)
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        // Supprimer les commandes en attente (state = 1) dont la dateStart est dépassée
        $expiredOrders = $em->getRepository(Order::class)
            ->createQueryBuilder('o')
            ->leftJoin('o.orderDetails', 'od')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->andWhere('o.state = 1')
            ->andWhere('od.dateStart < :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();

            foreach ($expiredOrders as $expiredOrder) {
                // Supprimer d'abord les OrderDetails liés
                foreach ($expiredOrder->getOrderDetails() as $orderDetail) {
                    $em->remove($orderDetail);
                }
                // Ensuite supprimer la commande elle-même
                $em->remove($expiredOrder);
            }
        $em->flush();
        
        // Construire le critère de recherche de commandes
        $criteria = ['user' => $user];
        if ($state !== null) {
            $criteria['state'] = $state;
        }
        
        // Compter le nombre total de commandes correspondant au filtre
        $totalOrders = $em->getRepository(Order::class)->count($criteria);
        $totalPages = ceil($totalOrders / $limit);
        
        // Récupérer les commandes avec pagination
        $orders = $em->getRepository(Order::class)
            ->createQueryBuilder('o')
            ->leftJoin('o.orderDetails', 'od') // Jointure avec OrderDetails
            ->addSelect('od') // Récupère également les details de commande
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->andWhere('o.state = :state') // Applique un filtre sur l'état de la commande
            ->setParameter('state', $state)
            ->orderBy('o.createAt', 'DESC')
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

         // Vérifier si la dateStart est passée et mettre à jour l'état
        $today = new \DateTime(); // Date actuelle
        foreach ($orders as $order) {
            // Parcourir les OrderDetails de chaque commande
            foreach ($order->getOrderDetails() as $orderDetail) {
                // Vérifier si dateStart est passée et si l'état est '2' (validée)
                if ($orderDetail->getDateStart() < $today && $order->getState() == 2) {
                    // Mettre à jour l'état de la commande
                    $order->setState(3); // Passer l'état à 3 (expédiée)
                    $em->persist($order); // Persister la commande mise à jour
                }
            }
        }
        
        // Enregistrer les modifications dans la base de données
        $em->flush();
        
        // Calculer le nombre de commandes par état
        $totalPending = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 1]);
        $totalValidated = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 2]);
        $totalCompleted = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 3]);
        $totalCancelled = $em->getRepository(Order::class)->count(['user' => $user, 'state' => 4]);
        
        return $this->render('account/reservation.html.twig', [
            'controller_name' => 'Vos Réservations',
            'orders' => $orders,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalOrders' =>$totalOrders,
            'totalOrders' => $totalOrders,
            'state' => $state,
            'totalPending' => $totalPending,
            'totalValidated' => $totalValidated,
            'totalCompleted' => $totalCompleted,
            'totalCancelled' => $totalCancelled,
        ]);
    }

    #[Route('/compte/reservation/annuler/{id_order}', name: 'app_account_cancel_order')]
    public function cancelOrder(int $id_order, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Récupérer la commande à partir de son ID
        $order = $em->getRepository(Order::class)->find([
            'id' => $id_order
        ]);
        
        // Vérifier si la commande existe et si son état est '1' (en attente de paiement)
        if (!$order || $order->getState() !== 1) {
            // Si la commande n'existe pas ou n'est pas dans l'état attendu, rediriger avec un message d'erreur
            $this->addFlash(
                'error',
                'Commande invalide ou déjà traitée.'
            );
            return $this->redirectToRoute('app_account_reservation');
        }

        // Mettre à jour l'état de la commande à '4' (annulée)
        $order->setState(4); // '4' correspond à l'état annulé
        $em->persist($order);
        $em->flush(); // Enregistrer les modifications

        // Ajouter un message de succès
        $this->addFlash(
            'success',
            'Commande annulée avec succès.'
        );
        
        // Rediriger l'utilisateur vers la page des réservations
        return $this->redirectToRoute('app_account_reservation');
    }
}