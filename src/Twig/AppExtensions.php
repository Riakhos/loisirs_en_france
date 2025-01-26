<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AppExtensions extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			new TwigFilter('price', [$this, 'formatPrice'])
		];
	}
	
	public function formatPrice($number)
	{
		return number_format($number, '2', ','). ' €';
	}
}