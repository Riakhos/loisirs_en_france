<?php

namespace App\Controller\Category;

use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubcategoryController extends AbstractController
{
    #[Route('/sous-categorie/{slug}', name: 'app_subcategory')]
    public function subcategory(string $slug, SubcategoryRepository $subcategoryRepository): Response
    {        
        // Trouver la sous-catégorie par son slug
        $subcategory = $subcategoryRepository->findOneBySlug($slug);

        // Si la sous-catégorie n'existe pas, redirigez vers la page d'accueil ou affichez une erreur
        if (!$subcategory) {
            $this->addFlash('error', "La sous-catégorie demandée n'existe pas."); // Message flash
            return $this->redirectToRoute('app_home'); // Redirection vers la page d'accueil
        }
        
        return $this->render('category/subcategory.html.twig', [
            'controller_name' => 'SubcategoryController',
            'subcategory' => $subcategory,
        ]);
    }
}