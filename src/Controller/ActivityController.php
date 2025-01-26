<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivityController extends AbstractController
{
    #[Route('/{categorySlug}/{subcategorySlug}/{activitySlug}', name: 'app_activity')]
    public function activity(string $categorySlug, string $subcategorySlug, string $activitySlug, CategoryRepository $categoryRepository,SubcategoryRepository $subcategoryRepository, ActivityRepository $activityRepository): Response
    {
        // Trouver la catégorie par son slug
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw $this->createNotFoundException('Category not found.');
            return $this->redirectToRoute('app_home');
        }

        // Trouver la sous-catégorie par son slug
        $subcategory = $subcategoryRepository->findOneBy(['slug' => $subcategorySlug]);

        if (!$subcategory) {
            throw $this->createNotFoundException('Subcategory not found.');
            return $this->redirectToRoute('app_home');
        }
        
        // Trouver l'activité' par son slug
        $activity = $activityRepository->findOneBy(['slug' => $activitySlug]);

        if (!$activity) {
            throw $this->createNotFoundException('Activity not found.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'category' => $category,
            'subcategory' => $subcategory,
            'activity' => $activity,
        ]);
    }
}