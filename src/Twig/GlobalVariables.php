<?php

namespace App\Twig;

use App\Classe\Cart;
use App\Repository\ActivityRepository;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;
use App\Repository\EventstrendRepository;
use App\Repository\SubcategoryRepository;
use App\Repository\TrendRepository;

class GlobalVariables extends AbstractExtension implements GlobalsInterface
{
	private CategoryRepository $categoryRepository;
	private SubcategoryRepository $subcategoryRepository;
	private ActivityRepository $activityRepository;
	private Cart $cart;
	private EventstrendRepository $eventstrendRepository;
	private TrendRepository $trendRepository;

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
	public function __construct(CategoryRepository $categoryRepository, SubcategoryRepository $subcategoryRepository,ActivityRepository $activityRepository, Cart $cart, EventstrendRepository $eventstrendRepository, TrendRepository $trendRepository)
	{
		$this->categoryRepository = $categoryRepository;
		$this->subcategoryRepository = $subcategoryRepository;
		$this->activityRepository = $activityRepository;
		$this->eventstrendRepository = $eventstrendRepository;
		$this->cart = $cart;
		$this->trendRepository = $trendRepository;
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
			'subcategories' => $this->subcategoryRepository->findAll(),
			'activities' => $this->activityRepository->findAll(),
			'eventstrends' => $this->eventstrendRepository->findAll(),
			'fullCartQuantity' => $this->cart->fullQuantity(),
			'trends' => $this->trendRepository->findAll(),
		];
	}
}