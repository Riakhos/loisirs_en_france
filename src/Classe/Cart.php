<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Classe Cart
 * Gère la gestion du panier via la session.
 */
class Cart
{
    /**
     * Constructeur de la classe Cart.
     * Injecte le service RequestStack pour accéder à la session.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(private RequestStack $requestStack) 
    {
    }

    /**
     * Récupère la session actuelle.
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private function getSession()
    {
        return $this->requestStack->getSession();
    }
    
    /**
     * Récupère le panier depuis la session.
     *
     * @return array Le panier sous forme de tableau associatif.
     */
    public function getCart(): array
    {
        return $this->getSession()->get('cart', []);
    }

    /**
     * Enregistre le panier dans la session.
     *
     * @param array $cart Le contenu du panier.
     * @return void
     */
    private function saveCart(array $cart): void
    {
        $this->getSession()->set('cart', $cart);
    }

    /**
     * Ajoute une activité au panier.
     *
     * @param object $activity L'activité à ajouter.
     * @return void
     */
    public function addActivity($activity): void
    {
        $cart = $this->getCart();
        $key = "activity_" . $activity->getId();
        
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'type' => 'activity',
                'object' => $activity,
                'qty' => 1
            ];
        }
        
        $this->saveCart($cart);
    }

    /**
     * Ajoute une offre spéciale au panier.
     *
     * @param object $offer L'offre spéciale à ajouter.
     * @return void
     */
    public function addOffer($offer): void
    {
        $cart = $this->getCart();
        $key = "offer_" . $offer->getId();
        
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'type' => 'offer',
                'object' => $offer,
                'qty' => 1
            ];
        }
        
        $this->saveCart($cart);
    }
    
    /**
     * Ajoute une offre exclusive au panier.
     *
     * @param object $exclusive L'offre exclusive à ajouter.
     * @return void
     */
    public function addExclusive($exclusive): void
    {
        $cart = $this->getCart();
        $key = "exclusive_" . $exclusive->getId();
        
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'type' => 'exclusive',
                'object' => $exclusive,
                'qty' => 1
            ];
        }
        
        $this->saveCart($cart);
    }
    
    /**
     * Ajoute un évènement spécial au panier.
     *
     * @param object $event L'évènement spécial à ajouter.
     * @return void
     */
    public function addEvent($event): void
    {
        $cart = $this->getCart();
        $key = "event_" . $event->getId();
        
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'type' => 'event',
                'object' => $event,
                'qty' => 1
            ];
        }
        
        $this->saveCart($cart);
    }
    
    /**
     * Supprime entièrement le panier de la session.
     *
     * @return void
     */
    public function remove(): void
    {
        $this->getSession()->remove('cart');
    }

    /**
     * Calcule le sous-total HT du panier (sans TVA).
     *
     * @return float Le montant total H.T.
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        $cart = $this->getCart();

        foreach ($cart as $item) {
            $subtotal += $item['object']->getPriceWt() * $item['qty'];
        }

        return $subtotal;
    }

    /**
     * Retourne les détails de la TVA par taux.
     *
     * @return array Tableau associatif contenant le taux de TVA en clé et le montant correspondant.
     */
    public function getTvaDetails(): array
    {
        $cart = $this->getCart();
        $tvaDetails = [];

        foreach ($cart as $item) {
            $priceHt = $item['object']->getPriceWt();
            $tvaRate = $item['object']->getTva();
            $quantity = $item['qty'];
            $tvaAmount = ($priceHt * $tvaRate / 100) * $quantity;
            
            if (!isset($tvaDetails[$tvaRate])) {
                $tvaDetails[$tvaRate] = 0;
            }
            $tvaDetails[$tvaRate] += $tvaAmount;
        }

        return $tvaDetails;
    }

    /**
     * Calcule le montant total de la TVA pour le panier.
     *
     * @return float Le montant total de la TVA.
     */
    public function getTva(): float
    {
        return array_sum($this->getTvaDetails());
    }

    /**
     * Retourne le total TTC du panier.
     *
     * @return float Le montant total TTC.
     */
    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getTva();
    }

    /**
     * Diminue la quantité d'un élément dans le panier.
     *
     * @param string $type Le type de l'élément.
     * @param int $id L'identifiant de l'élément.
     * @return void
     */
    public function decrease(string $type, int $id): void
    {
        $cart = $this->getCart();
        $key = $type . '_' . $id;

        if (isset($cart[$key])) {
            if ($cart[$key]['qty'] > 1) {
                $cart[$key]['qty']--;
            } else {
                unset($cart[$key]);
            }
        }
        
        $this->saveCart($cart);
    }

    /**
     * Retourne le nombre total d'éléments dans le panier.
     *
     * @return int Le nombre total d'éléments.
     */
    public function fullQuantity(): int
    {
        $cart = $this->getCart();
        $quantity = 0;
        
        foreach ($cart as $item) {
            $quantity += $item['qty'];
        }
        
        return $quantity;
    }
}