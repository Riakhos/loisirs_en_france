<?php

namespace App\Twig;

use App\Classe\Cart;
use App\Entity\Partner;
use App\Form\PartnerType;
use App\Form\SearchAllType;
use App\Repository\TrendRepository;
use App\Repository\RatingRepository;
use Twig\Extension\GlobalsInterface;
use App\Repository\PartnerRepository;
use Twig\Extension\AbstractExtension;
use App\Repository\ActivityRepository;
use App\Repository\CategoryRepository;
use App\Repository\ExclusiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventstrendRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GlobalVariables extends AbstractExtension implements GlobalsInterface
{
	private Cart $cart;
	private CategoryRepository $categoryRepository;
	private SubcategoryRepository $subcategoryRepository;
	private ActivityRepository $activityRepository;
	private EventstrendRepository $eventstrendRepository;
	private TrendRepository $trendRepository;
	private ExclusiveRepository $exclusiveRepository;
	private FormFactoryInterface $formFactory;
	private EntityManagerInterface $em;
	private PartnerRepository $partnerRepository;
	private RatingRepository $ratingRepository;
	private RequestStack $requestStack;

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
	public function __construct(
		CategoryRepository $categoryRepository, 
		SubcategoryRepository $subcategoryRepository,
		ActivityRepository $activityRepository, 
		Cart $cart, 
		EventstrendRepository $eventstrendRepository, 
		TrendRepository $trendRepository,
		ExclusiveRepository $exclusiveRepository,
		FormFactoryInterface $formFactory, 
		EntityManagerInterface $em,
		PartnerRepository $partnerRepository,
		RatingRepository $ratingRepository,
		RequestStack $requestStack
	)
	
	{
		$this->cart = $cart;
		$this->categoryRepository = $categoryRepository;
		$this->subcategoryRepository = $subcategoryRepository;
		$this->activityRepository = $activityRepository;
		$this->eventstrendRepository = $eventstrendRepository;
		$this->trendRepository = $trendRepository;
		$this->exclusiveRepository = $exclusiveRepository;
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->partnerRepository = $partnerRepository;
		$this->ratingRepository = $ratingRepository;
		$this->requestStack = $requestStack;
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
		// Création d'un formulaire Partner
		$partner = new Partner();
		$partnerForm  = $this->formFactory->create(PartnerType::class, $partner);

		$searchForm = $this->formFactory->create(SearchAllType::class);
	
		// Récupération des évaluations triées par date (les plus récentes en premier)
		$ratings = $this->ratingRepository->findBy([], ['createdAt' => 'DESC']);
	
		// Pagination des avis
		$request = $this->requestStack->getCurrentRequest();
		$page = max(1, $request ? $request->query->getInt('page', 1) : 1);
		$totalRatings = count($ratings);
		$totalPages = max(1, ceil($totalRatings / 1));
		
		$currentRating = ($page <= $totalRatings) ? $ratings[$page - 1] : null;
	
		// Calcul des étoiles pour l'avis courant
		$fullStars = 0;
		$hasHalfStar = false;
		$emptyStars = 5;
	
		if ($currentRating) {
			$score = $currentRating->getScore();
			$fullStars = floor($score);
			$hasHalfStar = ($score - $fullStars) >= 0.5;
			$emptyStars = 5 - $fullStars - ($hasHalfStar === 0.5 ? 1 : 0);
		}
				
		return [
			'categories' => $this->categoryRepository->findAll(),
			'subcategories' => $this->subcategoryRepository->findAll(),
			'activities' => $this->activityRepository->findAll(),
			'eventstrends' => $this->eventstrendRepository->findAll(),
			'trends' => $this->trendRepository->findAll(),
			'exclusives' => $this->exclusiveRepository->findAll(),
			'fullCartQuantity' => $this->cart->fullQuantity(),
			'partnerForm' => $partnerForm ->createView(),
			'partners' => $this->partnerRepository->findAll(),
			'ratings' => $this->ratingRepository->findAll(),
			'currentRating' => $currentRating,
			'currentPage' => $page,
			'totalPages' => $totalPages,
			'fullStars' => $fullStars,
			'hasHalfStar' => $hasHalfStar,
			'emptyStars' => $emptyStars,
			'searchForm' => $searchForm->createView(),
		];
	}
}