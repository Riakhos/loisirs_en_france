<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\OfferRepository;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CartController
     * *Ce contrôleur gère toutes les actions liées au panier
         * ?Telles que l'affichage des articles
         * ?L'ajout des articles
         * ?La suppression des articles
         * ?La diminution.
 */
class CartController extends AbstractController
{
    /**
     * app-cart
         * *Affiche le contenu du panier
     *
     * @param Cart $cart 
         * *Service de gestion du panier
     * @return Response 
         * *Retourne une réponse HTTP contenant la vue du panier
     */
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(Cart $cart): Response
    {
        // Calcul des sous-totaux, TVA et total
        $subtotal = $cart->getSubtotal();
        $tva = $cart->getTva();
        $tvaDetails = $cart->getTvaDetails();
        $total = $cart->getTotal();
        
        // Rendu de la vue Twig avec les données du panier
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'Mon panier',
            'cart' => $cart->getCart(),
            'subtotal' => $subtotal,
            'tva' => $tva,
            'tvaDetails' => $tvaDetails,
            'total' => $total,
        ]);
    }

    /**
     * app_cart_add
         * *Ajoute une activité au panier.
     *
     * @param int $id 
         * *L'identifiant de l'activité à ajouter.
     * @param Cart $cart
         * *Service de gestion du panier.
     * @param ActivityRepository $activityRepository
         * *Repository pour accéder aux activités.
     * @param Request $request
         * *La requête HTTP.
     * @return Response
         * *Redirige vers la page précédente après ajout.
     */
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, Cart $cart, ActivityRepository $activityRepository, Request $request): Response
    {
        // Recherche de l'activité par son ID
        $activity = $activityRepository->findOneById($id);

        // Ajout de l'activité au panier
        $cart->add($activity);

        // Message flash pour indiquer le succès de l'ajout
        $this->addFlash(
            'success',
            'L\'activité a été correctement ajoutée à votre panier.'
        );
        
        // Redirection vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * 
     */
    #[Route('/cart/add-offer/{id}', name: 'app_cart_add_offer')]
    public function addOffer(int $id, Cart $cart, OfferRepository $offerRepository, Request $request): Response
    {
        // Recherche de l'offre spéciale par son ID
        $offer = $offerRepository->findOneById($id);

        // Ajout de l'offre spéciale au panier
        $cart->addOffer($offer);

        // Message flash pour indiquer le succès de l'ajout
        $this->addFlash(
            'success',
            'L\'offre spéciale a été correctement ajoutée à votre panier.'
        );
        
        // Redirection vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * app_cart_decrease
         * *Diminue la quantité d'une activité dans le panier.
     *
     * @param int $id
         * *L'identifiant de l'activité à diminuer.
     * @param Cart $cart
         * *Service de gestion du panier.
     * @return Response
         * *Redirige vers la page du panier après modification.
     */
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        // Réduction de la quantité de l'article dans le panier
        $cart->decrease($id);

        // Message flash pour indiquer le succès de l'opération
        $this->addFlash(
            'success',
            'Activité correctement supprimée de votre panier.'
        );
        
        // Redirection vers la page du panier
        return $this->redirectToRoute('app_cart');
    }
    
    /**
     * 
     */
    #[Route('/cart/decrease_offer/{id}', name: 'app_cart_decrease_offer')]
    public function decreaseOffer($id, Cart $cart): Response
    {
        // Réduction de la quantité de l'article dans le panier
        $cart->decrease($id);

        // Message flash pour indiquer le succès de l'opération
        $this->addFlash(
            'success',
            'Offre spéciale correctement supprimée de votre panier.'
        );
        
        // Redirection vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * app_cart_remove
         * *Supprime totalement le panier.
     *
     * @param Cart $cart
         * *Service de gestion du panier.
     * @return Response
         * *Redirige vers la page du compte après suppression.
     */
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        // Suppression complète du panier
        $cart->remove();
        
        // Redirection vers la page du compte utilisateur
        return $this->redirectToRoute('app_account');
    }
}