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
	 * getSession()
		 * *Récupère la session actuelle
	 *
	 * @return
	 */
	private function getSession()
    {
        return $this->requestStack->getSession();
    }
	
	/**
	 * getCart()
		 * *Récupère le panier depuis la session
	 *
	 * @return array
		 * *Le panier (tableau associatif) ou null s'il est vide
	 */
	public function getCart(): array
	{
		return $this->requestStack->getSession()->get('cart', []);
	}

	/**
	 * saveCart()
		 * *Enregistre le panier dans la session
	 *
	 * @param array $cart
	 * @return void
	 */
	private function saveCart(array $cart): void
    {
        $this->getSession()->set('cart', $cart);
    }

	/**
	 * addActivity()
		 * *Ajoute un élément activité au panier
	 *
	 * @param [type] $item
	 * @param string $type
	 * @return void
	 */
	public function addActivity($activity): void
    {
        $cart = $this->getCart();
        $id = $activity->getId();

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'object' => $activity,
                'qty' => 1
            ];
        }

        $this-> saveCart($cart);
    }
		
	
	
	/**
	 * addOffer()
		 * *Fonction permettant l'ajout d'une offre spéciale au panier
	 *
	 * @param [type] $offer
		 * * *L'objet représentant l'offre spéciale à ajouter.
	 * @return void
	 */
	public function addOffer($offer): void
    {
		$cart = $this->getCart();
        $id = $offer->getId();

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'object' => $offer,
                'qty' => 1
            ];
        }

        $this-> saveCart($cart);
    }
	
	/**
	 * remove
		 * *Supprime entièrement le panier de la session
	 *
	 * @return void
	 */
	public function remove()
	{
		return $this->getSession()->remove('cart');
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
            $subtotal += $item['object']->getPriceWt() * $item['qty'];
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
			$priceHt = $activity->getPriceWt(); //* Prix HT de l'activité
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

	/**
	 * getTotal()
	 * *Retourne le total TTC du panier
	 *
	 * @return float
	 */
    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getTva();
    }

	/**
	 * decrease()
	 	 * *Diminue la quantité d'une activité dans le panier
     	 * *Si la quantité atteint 1, l'activité est supprimée
	 *
	 * @param int $id
		 * *L'identifiant de l'activité à diminuer
	 * @return void
	 */
	public function decrease(int $id): void
    {
		// Récupération du panier
        $cart = $this->getCart();

		// Vérification si l'activité existe dans le panier
        if ($cart[$id]['qty'] > 1) {
			// Réduction de la quantité
            // $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
            $cart[$id]['qty']--;
        } else {
			// Suppression de l'activité si la quantité atteint 1
            unset($cart[$id]);
        }

		// Mise à jour du panier dans la session
        $this->saveCart($cart);
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
        
        foreach ($cart as $item) {
            $quantity = $quantity + $item['qty'];
        }
        return $quantity;
    }
}