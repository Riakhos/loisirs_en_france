<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ViewRatingController extends AbstractController
{
    #[Route('/afficher-avis', name: 'app_view_rating')]
    public function index(): Response
    {
        return $this->render('home/viewrating.html.twig', [
            'controller_name' => 'Voir les derniers Avis',
        ]);
    }
}