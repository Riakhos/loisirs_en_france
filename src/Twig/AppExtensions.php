<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AppExtensions extends AbstractExtension
{
	/**
	 * getFilters
	 	* * Renvoie une liste de filtres Twig personnalisés définis dans cette extension
	 *
	 * @return array
	 	* * Un tableau contenant les filtres Twig
	 */
	public function getFilters(): array
	{
		return [
			// Déclaration d'un filtre Twig nommé "price"
			new TwigFilter('price', [$this, 'formatPrice'])
		];
	}
	
	/**
	 * formatPrice
		* *Formate un nombre pour l'affichage comme un prix en euros.
		* *Ajoute deux décimales, une virgule comme séparateur décimal et un espace suivi du symbole €
	 *
	 * @param float|int $number
		 * *Le nombre à formater
	 * @return string 
	 	* *Le nombre formaté en tant que chaîne avec un format monétaire
	 */
	public function formatPrice($number)
	{
		// Utilisation de la fonction PHP `number_format` pour le formatage
		return number_format($number, '2', ','). ' €';
	}
}