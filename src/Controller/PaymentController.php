<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\User;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/commande/paiement/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        
        $order = $orderRepository->findWithDetails( $id_order, $this->getUser());

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        
        $product_for_stripe = [];
        
        foreach ($order->getOrderDetails() as $product) {
            
            // Ajouter le produit à la liste pour Stripe
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format($product->getProductPrice() * 100, 0, '', ''),  // En centimes
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'].'/uploads/'.$product->getProductImage()
                        ]
                    ]
                ],
                'quantity' => $product->getProductQuantity()
            ];
        }
        
        /** @var User $user */
        $user = $this->getUser();
        
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'].'/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'].'/mon-panier/annulation',
        ]);
        
        $order->setStripeSessionId($checkout_session->id);
        $em->flush();

        return $this->redirect($checkout_session->url);
    }

    #[Route('/commande/merci/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $em, Cart $cart): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 1 ) {
            $order->setState(2);
            $cart->remove();
            $em->flush();
        }

        
        return $this->render('order/success.html.twig', [
            'order' => $order
        ]);
    }
}