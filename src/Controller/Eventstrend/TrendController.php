<?php

namespace App\Controller\Eventstrend;

use App\Repository\TrendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrendController extends AbstractController
{
    #[Route('/activites-tendances/{slug}', name: 'app_trend')]
    public function trend(string $slug, TrendRepository $trendRepository): Response
    {
        // Trouver l'activité tendance par son slug
        $trend = $trendRepository->findOneBySlug($slug);

        // Si l'activité tendance n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$trend) {
            $this->addFlash('error', 'Activité tendance introuvable.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('eventstrend/trend.html.twig', [
            'controller_name' => 'Activités Tendances',
            'trend' => $trend,
            'activities' => $trend->getActivities(),
        ]);
    }
}