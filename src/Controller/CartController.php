<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(Cart $cart,): Response
    {
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart->getCart()
        ]);
    }
    
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, Cart $cart, ActivityRepository $activityRepository): Response
    {
        $activity = $activityRepository->findOneById($id);
        
        $cart->add($activity);

        $this->addFlash(
            'success',
            'L\'activité a été correctement ajouté à votre panier.'
        );
        
        return $this->redirectToRoute('app_activity', [
            'slug' => $activity->getSlug()
        ]);
    }
}