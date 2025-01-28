<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(Cart $cart,): Response
    {
        $subtotal = $cart->getSubtotal();
        $tva = $cart->getTva();
        $tvaDetails = $cart->getTvaDetails();
        $total = $cart->getTotal();
        
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart->getCart(),
            'subtotal' => $subtotal,
            'tva' => $tva,
            'tvaDetails' => $tvaDetails,
            'total' => $total,
        ]);
    }
    
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, Cart $cart, ActivityRepository $activityRepository, Request $request): Response
    {
        $activity = $activityRepository->findOneById($id);
        
        $cart->add($activity);

        $this->addFlash(
            'success',
            'L\'activité a été correctement ajoutée à votre panier.'
        );
        
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Activité correctement supprimée de votre panier.'
        );
        
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        
        return $this->redirectToRoute('app_account');
    }
}