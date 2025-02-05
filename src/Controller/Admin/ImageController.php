<?php 

namespace App\Controller\Admin; 

use App\Service\ImageOptimizer; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 

class ImageController extends AbstractController 
{ 
	private $imageOptimizer;
	
	public function __construct(ImageOptimizer $imageOptimizer) 
	{ 
		$this->imageOptimizer = $imageOptimizer;
	}

	/**
	* @Route("/admin/image-upload", name="image_upload")
	*/
	public function uploadImage(Request $request): Response
	{
		$file = $request->files->get('image');

		if (!$file instanceof UploadedFile) {
			return new Response('Aucun fichier valide reçu', Response::HTTP_BAD_REQUEST);
		}

		// Vérification MIME pour éviter les fichiers non-images
		$mimeType = $file->getMimeType();
		if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
			return new Response('Format de fichier non supporté', Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
		}

		// Définition du répertoire de destination
		$uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/';

		// Génération d'un nom de fichier unique avec extension d'origine
		$originalExtension = $file->guessExtension();
		$uniqueFilename = uniqid('image_', true) . '.' . $originalExtension;
		$destination = $uploadDir . $uniqueFilename;

		// Déplacement du fichier vers le répertoire d'upload
		$file->move($uploadDir, $uniqueFilename);

		// Redimensionnement de l'image
		try {
			$this->imageOptimizer->resize($destination);
		} catch (\Exception $e) {
			return new Response('Erreur lors du redimensionnement : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		// Conversion en WebP
		$webpFilename = $this->imageOptimizer->convertToWebP($destination);

		if (!$webpFilename) {
			return new Response('Erreur lors de la conversion en WebP', Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return new Response('Image traitée avec succès : ' . basename($webpFilename));
	}

}