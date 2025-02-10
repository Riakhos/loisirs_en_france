<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\EventRepository;
use App\Repository\OfferRepository;
use App\Repository\ActivityRepository;
use App\Repository\ExclusiveRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur gérant les actions liées au panier.
 */
class CartController extends AbstractController
{
    private Cart $cart;
    private ActivityRepository $activityRepository;
    private OfferRepository $offerRepository;
    private ExclusiveRepository $exclusiveRepository;
    private EventRepository $eventRepository;

    public function __construct(
        Cart $cart,
        ActivityRepository $activityRepository,
        OfferRepository $offerRepository,
        ExclusiveRepository $exclusiveRepository,
        EventRepository $eventRepository
    ) {
        $this->cart = $cart;
        $this->activityRepository = $activityRepository;
        $this->offerRepository = $offerRepository;
        $this->exclusiveRepository = $exclusiveRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Affiche le contenu du panier.
     *
     * @return Response Retourne une réponse HTTP contenant la vue du panier.
     */
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(): Response
    {
        // Calcul des sous-totaux, TVA et total
        $subtotal = $this->cart->getSubtotal();
        $tva = $this->cart->getTva();
        $tvaDetails = $this->cart->getTvaDetails();
        $total = $this->cart->getTotal();
        
        // Récupération du panier (les objets dans le panier)
        $cartItems = $this->cart->getCart();
        
        // Tableau pour stocker les activités liées aux exclusivités
        $exclusiveActivities = [];

        // Stocker les offres enrichies
        $enrichedOffers = [];

        // Parcours des éléments du panier
        foreach ($cartItems as &$item) {
            $type = $item['type'];
            
            if ($type === 'exclusive') {
                $exclusive = $this->exclusiveRepository->findWithActivities($item['object']->getId());
        
                if ($exclusive) {
                    // Remplacer l'objet dans le panier par celui avec les activités chargées
                    $item['object'] = $exclusive;
        
                    // Stocker les activités dans le tableau
                    $exclusiveActivities[$exclusive->getId()] = $exclusive->getActivities()->toArray();
                }
            }

            if ($type === 'offer') {
                // Charger l'offre avec ses relations
                $offer = $this->offerRepository->findWithRelations($item['object']->getId());

                if ($offer) {
                    $item['object'] = $offer;
                    // Stocker l'offre enrichie pour la vue
                    $enrichedOffers[$offer->getId()] = [
                        'partners' => $offer->getPartners(),
                        'activities' => $offer->getActivity()->toArray(),
                    ];
                }
            }
        }
        
        // Rendu de la vue Twig avec les données du panier
        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'Mon panier',
            'cart' => $cartItems,
            'subtotal' => $subtotal,
            'tva' => $tva,
            'tvaDetails' => $tvaDetails,
            'total' => $total,
            'exclusiveActivities' => $exclusiveActivities,
            'enrichedOffers' => $enrichedOffers,
        ]);
    }

    /**
     * Ajoute un élément au panier.
     *
     * @param string $type Le type de l'élément (activity, offer, exclusive).
     * @param int $id L'identifiant de l'élément à ajouter.
     * @param Request $request La requête HTTP.
     * @return Response Redirige vers la page précédente après l'ajout.
     */
    #[Route('/cart/add/{type}/{id}', name: 'app_cart_add')]
    public function add(string $type, int $id, Request $request): Response
    {
        $repositories = [
            'offer' => $this->offerRepository,
            'activity' => $this->activityRepository,
            'exclusive' => $this->exclusiveRepository,
            'event' => $this->eventRepository
        ];

        if (!isset($repositories[$type])) {
            $this->addFlash('error', "Type d'élément invalide spécifié.");
            return $this->redirect($request->headers->get('referer'));
        }

        $object = $repositories[$type]->find($id);

        if (!$object) {
            $this->addFlash('error', ucfirst($type) . " avec l'ID $id n'existe pas.");
            return $this->redirect($request->headers->get('referer'));
        }

        $addMethod = 'add' . ucfirst($type);
        $this->cart->$addMethod($object);

        $this->addFlash('success', ucfirst($type) . " a été ajouté à votre panier.");

        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * Diminue la quantité d'un élément dans le panier.
     *
     * @param string $type Le type de l'élément (activity, offer, exclusive).
     * @param int $id L'identifiant de l'élément à diminuer.
     * @return Response Redirige vers la page du panier après modification.
     */
    #[Route('/cart/decrease/{type}/{id}', name: 'app_cart_decrease')]
    public function decrease(string $type, int $id): Response
    {
        $cart = $this->cart->getCart();

        // Reconstruire la clé correctement
        $key = $type . '_' . $id;
        
        if (!isset($cart[$key])) {
            $this->addFlash(
                'error',
                "L'élément avec l'ID $id ($type) n'est pas dans le panier."
            );
        } else {
            // Réduction de la quantité de l'élément dans le panier
            $this->cart->decrease($type, $id);

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
     * Supprime totalement le panier.
     *
     * @return Response Redirige vers la page du compte après suppression.
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