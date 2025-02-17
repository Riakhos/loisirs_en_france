<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ViewRatingController extends AbstractController
{
    #[Route('/view/rating', name: 'app_view_rating')]
    public function index(): Response
    {
        return $this->render('home/viewrating.html.twig', [
            'controller_name' => 'ViewRatingController',
        ]);
    }
}