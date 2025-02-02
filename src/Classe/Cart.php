<?php

namespace App\Classe;

// use App\Entity\Offer;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
	/**
	 * __construct
		* *Constructeur de la classe Cart
		* *Injecte le service RequestStack pour accéder à la session
	 *
	 * @param RequestStack $requestStack
	 */
	public function __construct(private RequestStack $requestStack) 
	{
        // L'accès direct à la session dans le constructeur est déconseillé car la session pourrait ne pas être encore initialisée.
        // $this->session = $requestStack->getSession();
    }
	
	/**
	 * add
		 * *Fonction permettant l'ajout d'une activité au panier
	 *
	 * @param mixed $activity
		 * *L'objet représentant l'activité à ajouter.
     * @return void
	 */
	public function add($activity)
	{
		// Récupération de la session
		$session = $this->requestStack->getSession();
		
		// Récupération du panier actuel ou initialisation
        $cart = $session->get('cart', []);
		
		// ID unique de l'activité
        $activityId = $activity->getId();

		// Vérification si l'activité est déjà dans le panier
        if (isset($cart[$activityId])) {
            //* Incrémentation de la quantité
            $cart[$activityId]['qty']++;
        } else {
            //* Ajout d'une nouvelle activité avec une quantité initiale de 1
            $cart[$activityId] = [
                'object' => $activity,
                'qty' => 1
            ];
        }
		
		// Mise à jour du panier dans la session
        $session->set('cart', $cart);
	}
	
	/**
	 * getCart
		 * *Retourne le contenu du panier
	 *
	 * @return mixed
		 * *Le panier (tableau associatif) ou null s'il est vide
	 */
	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart');
	}
	
	/**
	 * remove
		 * *Supprime entièrement le panier de la session
	 *
	 * @return void
	 */
	public function remove()
	{
		return $this->requestStack->getSession()->remove('cart');
	}

	/**
	 * getSubtotal
		 * *Calcule le sous-total HT du panier (sans TVA) 
	 *
	 * @return float
		 * *Le montant total H.T
	 */
	public function getSubtotal(): float
    {
        $subtotal = 0;
        $cart = $this->getCart() ?? [];

		// Parcours des éléments du panier pour calculer le total H.T
        foreach ($cart as $item) {
            $subtotal += $item['object']->getPrice() * $item['qty'];
        }

        return $subtotal;
    }

	/**
	 * getTvaDetails
		 * *Retourne les détails de la TVA par taux
	 *
	 * @return array
		 * *Tableau associatif contenant le taux de TVA en clé et le montant de TVA correspondant en valeur
	 */
	public function getTvaDetails(): array
	{
		$cart = $this->getCart() ?? [];
		$tvaDetails = [];

		foreach ($cart as $item) {
			$activity = $item['object'];
			$priceHt = $activity->getPrice(); //* Prix HT de l'activité
			$tvaRate = $activity->getTva();  //* Taux de TVA
			$quantity = $item['qty'];        //* Quantité dans le panier

			// Calcul du montant de TVA pour l'article
			$tvaAmount = ($priceHt * $tvaRate / 100) * $quantity;

			// Ajout au tableau regroupant les montants par taux de TVA
			if (!isset($tvaDetails[$tvaRate])) {
				$tvaDetails[$tvaRate] = 0;
			}
			$tvaDetails[$tvaRate] += $tvaAmount;
		}

		return $tvaDetails;
	}


	/**
	 * getTva
		 * *Calcule le montant total de la TVA pour le panier
	 *
	 * @return float
		 * *Le montant total de la TVA
	 */
    public function getTva(): float
	{
		$tvaDetails = $this->getTvaDetails();
		return array_sum($tvaDetails);; //* Somme des montants de TVA
	}

    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getTva();
    }

	/**
	 * decrease
	 	 * *Diminue la quantité d'une activité dans le panier
     	 * *Si la quantité atteint 1, l'activité est supprimée
	 *
	 * @param int $id
		 * *L'identifiant de l'activité à diminuer
	 * @return void
	 */
	public function decrease($id)
    {
		// Récupération du panier
        $cart = $this->getCart();

		// Vérification si l'activité existe dans le panier
        if ($cart[$id]['qty'] > 1) {
			// Réduction de la quantité
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
			// Suppression de l'activité si la quantité atteint 1
            unset($cart[$id]);
        }

		// Mise à jour du panier dans la session
        $this->requestStack->getSession()->set('cart', $cart);
    }

	/**
     * fullQuantity
     	* *Fonction retournant le nombre total d'activité dans le panier
     *
     * @return mixed
     */
    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;
        
        if (!isset($cart)) {
            return $quantity;
        }
        
        foreach ($cart as $activity) {
            $quantity = $quantity + $activity['qty'];
        }
        
        return $quantity;
    }

	/**
	 * addOffer
	 	* *Fonction permettant l'ajout d'une offre spéciale au panier
	 *
	 * @param [type] $offer
		 * *L'objet représentant l'offre spéciale à ajouter.
	 * @return void
	 */
	public function addOffer($offer)
	{
		// Récupération de la session
		$session = $this->requestStack->getSession();
		
		// Récupération du panier actuel ou initialisation
        $cart = $session->get('cart', []);
		
		// ID unique de l'offre spéciale
		$offerId = $offer->getId();

		// Vérification si l'offre spéciale est déjà dans le panier
        if (isset($cart[$offerId])) {
            //* Incrémentation de la quantité
            $cart[$offerId]['qty']++;
        } else {
            //* Ajout d'une nouvelle offre spéciale avec une quantité initiale de 1
            $cart[$offerId] = [
                'object' => $offer,
                'qty' => 1
            ];
        }
		
		// Mise à jour du panier dans la session
        $session->set('cart', $cart);
	}
}