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
}