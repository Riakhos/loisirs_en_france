<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubcategoryController extends AbstractController
{
    #[Route('/{categorySlug}/{subcategorySlug}', name: 'app_subcategory')]
    public function subcategory(string $categorySlug, CategoryRepository$categoryRepository, string $subcategorySlug, SubcategoryRepository $subcategoryRepository): Response
    {
        // Trouver la catégorie par son slug
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw $this->createNotFoundException('Category not found.');
        }

        // Trouver la sous-catégorie par son slug
        $subcategory = $subcategoryRepository->findOneBy(['slug' => $subcategorySlug]);

        if (!$subcategory) {
            throw $this->createNotFoundException('Subcategory not found.');
        }
        
        return $this->render('subcategory/subcategory.html.twig', [
            'controller_name' => 'SubcategoryController',
            'category' => $category,
            'subcategory' => $subcategory,
        ]);
    }
}