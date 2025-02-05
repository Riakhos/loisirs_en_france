<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
	private const MAX_WIDTH = 600;
	private const MAX_HEIGHT = 600;

	private $imagine;

	public function __construct()
	{
		$this->imagine = new Imagine();
	}

	public function resize(string $filename): void
	{
		if (!file_exists($filename) || !is_readable($filename)) {
			throw new \InvalidArgumentException("Le fichier n'existe pas ou n'est pas lisible.");
		}

		$size = getimagesize($filename);
		if ($size === false) {
			throw new \RuntimeException("Impossible d'obtenir les dimensions de l'image.");
		}

		list($iwidth, $iheight) = $size;
		$ratio = $iwidth / $iheight;
		$width = self::MAX_WIDTH;
		$height = self::MAX_HEIGHT;

		if ($width / $height > $ratio) {
			$width = $height * $ratio;
		} else {
			$height = $width / $ratio;
		}

		$photo = $this->imagine->open($filename);
		$photo->resize(new Box($width, $height))->save($filename);
	}


	public function convertToWebP(string $filePath): ?string
	{
		$webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $filePath);
		
		try {
			error_log("Conversion de l'image en WebP : " . $filePath);

			$image = imagecreatefromstring(file_get_contents($filePath));
			if (!$image) {
				error_log("Erreur : Impossible de créer une image à partir de {$filePath}");
				return null;
			}

			if (!imagewebp($image, $webpPath, 80)) {
				error_log("Erreur : La conversion en WebP a échoué pour {$filePath}");
				return null;
			}

			imagedestroy($image);
			return $webpPath;
		} catch (\Exception $e) {
			error_log("Erreur lors de la conversion WebP : " . $e->getMessage());
			return null;
		}
	}
}