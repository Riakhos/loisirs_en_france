<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    private $orderService;
    private $cart;

    public function __construct(OrderService $orderService, Cart $cart)
    {
        $this->orderService = $orderService;
        $this->cart = $cart;
    }

    public function processOrder(Request $request, Cart $cart, OrderService $orderService): Response
    {
        $session = $request->getSession();  // Récupérer la session
        $orderData = $orderService->prepareOrderData($cart->getCart());

        // Passer la session et les autres données nécessaires à votre service
        $orderService->processOrder($orderData, $this->getUser(), $session);

        return $this->redirectToRoute('app_order_success');
    }

    /**
     * 1ère Étape du tunnel d'achat
     * chooseDateTime()
     * fonction pour passer une commande
     * 
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    #[Route('/commande/date-et-heure', name: 'app_order_date_time', methods: ['GET', 'POST'])]
    public function chooseDateTime(Request $request ,Cart $cart): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour ajouter une note.'
            );
            return $this->redirectToRoute('app_login');
        }
        
        $products = $cart->getCart(); // Récupérer les produits du panier (Activités, Événements, Offres)

        if (!$products) {
            $this->addFlash(
                'warning',
                'Votre panier est vide.'
            );
            return $this->redirectToRoute('app_cart');
        }
        
        // Préparation des données de commande
        $orderData = $this->orderService->prepareOrderData($products);
        
        // Calcul des détails de la commande
        $orderDetails = $this->orderService->calculateOrderDetails($this->cart);
        
        // Création du formulaire
        $orderForm = $this->createForm(OrderType::class, $orderData);
        $orderForm->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            // Récupérer la session via l'objet Request
            $session = $request->getSession();
            $session->set('orderData', $orderForm->getData());

            // Rediriger vers la deuxième étape du processus
            return $this->redirectToRoute('app_order_summary');
        }

        return $this->render('cart/order.html.twig', [
            'controller_name' => 'Passer une commande',
            'orderForm' => $orderForm->createView(),
            'cart' => $products,
            'subtotal' => $orderDetails['subtotal'],
            'tvaDetails' => $orderDetails['tvaDetails'],
            'totalTva' => $orderDetails['totalTva'],
            'total' => $orderDetails['total'],
        ]);
    }
    
    /**
     * 2ème Étape du tunnel d'achat : Récapitulatif + Validation finale
     * orderSummaryAndConfirm()
     * fonction pour résumer la commande et l'enregistrer
     *
     * @param Request $request
     * @param Cart $cart
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/commande/recapitulatif-et-validation', name: 'app_order_summary', methods: ['POST', 'GET'])]
    public function orderSummaryAndConfirm(Request $request, Cart $cart): Response
    {
        $session = $request->getSession();
        $orderData = $session->get('orderData');

        if (!$orderData) {
            $this->addFlash(
                'error',
                'Aucune commande en attente.'
            );
            return $this->redirectToRoute('app_order_date_time');
        }

        // Calcul des détails de la commande
        $orderDetails = $this->orderService->calculateOrderDetails($this->cart);
        
        $orderForm = $this->createForm(OrderType::class, $orderData);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            // Récupérer la session depuis la requête
            $session = $request->getSession();
        
            // Processus de création de la commande
            $this->orderService->processOrder($orderForm->getData(), $this->getUser(), $session);

            $this->addFlash(
                'success',
                'Votre commande a bien été enregistrée.'
            );
            return $this->redirectToRoute('app_account_reservation');
        }

        return $this->render('cart/summary.html.twig', [
            'controller_name' => 'Confirmer ma commande',
            'orderForm' => $orderForm->createView(),
            'cart' => $cart->getCart(),
            'subtotal' => $orderDetails['subtotal'],
            'tvaDetails' => $orderDetails['tvaDetails'],
            'totalTva' => $orderDetails['totalTva'],
            'total' => $orderDetails['total'],
        ]);
    }
}