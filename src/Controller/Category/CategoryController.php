<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        
        $category = $categoryRepository->findOneBySlug($slug);

        // Si la catégorie n'existe pas, redirigez vers la page d'accueil ou affichez une erreur
        if (!$category) {
            $this->addFlash('error', "La catégorie demandée n'existe pas."); // Message flash
            return $this->redirectToRoute('app_home'); // Redirection vers la page d'accueil
        }

        // Si la catégorie existe, afficher la page correspondante
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
        ]);
    }
}