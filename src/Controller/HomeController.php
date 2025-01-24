<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'category' => $category,
        ]);
    }
}