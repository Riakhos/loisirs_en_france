<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        
        $category = $categoryRepository->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas.");
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('category/category.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
        ]);
    }
}