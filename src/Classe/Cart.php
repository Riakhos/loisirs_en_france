<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
	public function __construct(private RequestStack $requestStack) 
	{
        // L'accès à la session dans le constructeur est *NON* recommandé, car elle pourrait ne pas être encore accessible ou entraîner des effets secondaires indésirables
        // $this->session = $requestStack->getSession();
    }
	
	public function add($activity)
	{
		// Appeler la session de symfony
		$session = $this->requestStack->getSession();
		
		// Récupérer le panier actuel (ou initialiser un tableau vide)
        $cart = $session->get('cart', []);
		
		// Vérifier si l'activité' existe déjà dans le panier
        $activityId = $activity->getId();
        if (isset($cart[$activityId])) {
            // Augmenter la quantité si l'activité' est déjà dans le panier
            $cart[$activityId]['qty']++;
        } else {
            // Ajouter l'activité' avec une quantité de 1
            $cart[$activityId] = [
                'object' => $activity,
                'qty' => 1
            ];
        }
		
		// Enregistrer le panier mis à jour dans la session
        $session->set('cart', $cart);
	}
	
	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart');
	}
	
	public function remove()
	{
		return $this->requestStack->getSession()->remove('cart');
	}

	public function getSubtotal(): float
    {
        $subtotal = 0;
        $cart = $this->getCart() ?? [];

        foreach ($cart as $item) {
            $subtotal += $item['object']->getPrice() * $item['qty'];
        }

        return $subtotal;
    }

	public function getTvaDetails(): array
	{
		$cart = $this->getCart() ?? [];
		$tvaDetails = [];

		foreach ($cart as $item) {
			$activity = $item['object'];
			$priceHt = $activity->getPrice();
			$tvaRate = $activity->getTva(); // Taux de TVA
			$quantity = $item['qty'];

			// Calcul de la TVA pour cet article
			$tvaAmount = ($priceHt * $tvaRate / 100) * $quantity;

			// Ajouter au tableau des TVA regroupées
			if (!isset($tvaDetails[$tvaRate])) {
				$tvaDetails[$tvaRate] = 0;
			}
			$tvaDetails[$tvaRate] += $tvaAmount;
		}

		return $tvaDetails;
	}


    public function getTva(): float
	{
		$tvaDetails = $this->getTvaDetails();
		return array_sum($tvaDetails);
	}

    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getTva();
    }

	public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }
}