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
    private $cart;
    private $activityRepository;
    private $offerRepository;

    public function __construct(Cart $cart, ActivityRepository $activityRepository, OfferRepository $offerRepository)
    {
        $this->cart = $cart;
        $this->activityRepository = $activityRepository;
        $this->offerRepository = $offerRepository;
    }
    /**
     * app-cart
         * *Affiche le contenu du panier
     *
     * @return Response 
         * *Retourne une réponse HTTP contenant la vue du panier
     */
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(): Response
    {
        // Calcul des sous-totaux, TVA et total
        $subtotal = $this->cart->getSubtotal();
        $tva = $this->cart->getTva();
        $tvaDetails = $this->cart->getTvaDetails();
        $total = $this->cart->getTotal();
        
        // Rendu de la vue Twig avec les données du panier
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'Mon panier',
            'cart' => $this->cart->getCart(),
            'subtotal' => $subtotal,
            'tva' => $tva,
            'tvaDetails' => $tvaDetails,
            'total' => $total,
        ]);
    }

    /**
     * app_cart_addW
         * *Ajoute une activité au panier.
     *
     * @param int $id 
         * *L'identifiant de l'activité à ajouter.
     * @param Request $request
         * *La requête HTTP.
     * @return Response
         * *Redirige vers la page précédente après ajout.
     */
    #[Route('/cart/add/{type}/{id}', name: 'app_cart_add')]
    public function add(string $type, int $id, Request $request): Response
    {
        // Vérification du type (offer ou activity) et récupération de l'objet correspondant
        if ($type === 'offer') {
            // Recherche de l'offre par son ID
            $offer = $this->offerRepository->findOneById($id);

            if ($offer) {
                // Ajout de l'offre exclusive au panier
                $this->cart->addOffer($offer);

                // Message flash pour indiquer le succès de l'ajout
                $this->addFlash(
                    'success',
                    "L'offre exclusive a été correctement ajoutée à votre panier."
                );
            } else {
                // Message flash si l'offre n'est pas trouvée
                $this->addFlash(
                    'error',
                    "L'offre avec l'ID $id n'existe pas."
                );
            }
        } elseif ($type === 'activity') {
            // Recherche de l'activité par son ID
            $activity = $this->activityRepository->findOneById($id);

            if ($activity) {
                // Ajout de l'activité au panier
                $this->cart->addActivity($activity);

                // Message flash pour indiquer le succès de l'ajout
                $this->addFlash(
                    'success',
                    "L'activité a été correctement ajoutée à votre panier."
                );
            } else {
                // Message flash si l'activité n'est pas trouvée
                $this->addFlash(
                    'error',
                    "L'activité avec l'ID $id n'existe pas."
                );
            }
        } else {
            // Message flash si le type est invalide
            $this->addFlash(
                'error',
                "Type d'élément invalide spécifié."
            );
        }
        
        // Redirection vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * app_cart_decrease
         * *Diminue la quantité d'une activité dans le panier.
     *
     * @param int $id
         * *L'identifiant de l'activité à diminuer.
     * @return Response
         * *Redirige vers la page du panier après modification.
     */
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease(int $id): Response
    {
        $cart = $this->cart->getCart();

        if (!isset($cart[$id])) {
            $this->addFlash('error', "L'élément avec l'ID $id n'est pas dans le panier.");
        } else {
            
            // Réduction de la quantité de l'élément dans le panier
            $this->cart->decrease($id);

            // Message flash pour indiquer le succès de l'opération
            $this->addFlash(
                'success',
                'Loisir correctement supprimé de votre panier.'
            );
        }
        
        // Redirection vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    /**
     * app_cart_remove
         * *Supprime totalement le panier.
     *
     * @return Response
         * *Redirige vers la page du compte après suppression.
     */
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(): Response
    {
        // Suppression complète du panier
        $this->cart->remove();
        
        // Redirection vers la page du compte utilisateur
        return $this->redirectToRoute('app_account');
    }
}