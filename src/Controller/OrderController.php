<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\Activity;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    /**
     * 1ère Étape du tunnel d'achat
     * chooseDateTime()
     * fonction pour passer une commande
     *
     * @param Request $request
     * @param Cart $cart
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/commande/date-et-heure', name: 'app_order_date_time')]
    public function chooseDateTime(Request $request, Cart $cart, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $products = $cart->getCart(); // Récupérer les produits du panier (Activités, Événements, Offres)

        // Création des données pour le formulaire
        $orderData = ['items' => []];
        
        foreach ($products as $product) {
            $orderData['items'][] = [
                'itemId' => $product['object']->getId(),
                'name' => $product['object']->getName(),
                'dateStart' => new \DateTime(), // Date par défaut (ajustable)
                'time' => new \DateTime(), // Heure par défaut
            ];
        }
        
        $orderForm = $this->createForm(OrderType::class, $orderData);

        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            // Créer un objet Order pour enregistrer ces informations
            $order = new Order();
            $order->setUser($user);
            
            foreach ($orderForm->get('items')->getData() as $itemData) {
                $orderDetail = new OrderDetail();
                $orderDetail->setItemId($itemData['itemId']);
                $orderDetail->setDateStart($itemData['dateStart']);
                $orderDetail->setTime($itemData['time']);
                $orderDetail->setMyOrder($order);
    
                $em->persist($orderDetail);
            }
            
            $em->persist($order);
            $em->flush();
            
            // Rediriger vers la page suivante (révision de la commande, paiement, etc.)
            return $this->redirectToRoute('app_order_summary');
        }

        return $this->render('cart/order.html.twig', [
            'controller_name' => 'Passer une commande',
            'orderForm' => $orderForm->createView(),
            'cart' => $products,
        ]);
    }

}