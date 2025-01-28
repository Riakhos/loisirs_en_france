<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class GlobalVariables extends AbstractExtension implements GlobalsInterface
{
	private CategoryRepository $categoryRepository;
	private Cart $cart;

	/**
	 * __construct
	 	* *Constructeur de la classe GlobalVariables
	 	* *Injecte les dépendances nécessaires : le repository des catégories et le service de gestion du panier
	 *
	 * @param CategoryRepository $categoryRepository
	 	* *Le repository pour accéder aux catégories
	 * @param Cart $cart
	 	* *Le service de gestion du panier
	 */
	public function __construct(CategoryRepository $categoryRepository, Cart $cart)
	{
		$this->categoryRepository = $categoryRepository;
		$this->cart = $cart;
	}

	/**
	 * getGlobals
	 	* *Fournit des variables globales accessibles dans les templates Twig
	 	* *Les variables retournées ici sont automatiquement disponibles dans toutes les vues Twig
	 *
	 * @return array
		* *Un tableau associatif contenant les variables globales :
			** - 'categories' : Liste de toutes les catégories (issues de la base de données).
			** - 'fullCartQuantity' : Quantité totale d'articles dans le panier.
	 */
	public function getGlobals(): array
	{
		return [
			'categories' => $this->categoryRepository->findAll(),
			'fullCartQuantity' => $this->cart->fullQuantity(),
		];
	}
}