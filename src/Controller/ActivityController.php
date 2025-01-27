<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivityController extends AbstractController
{
    #[Route('/activite/{slug}', name: 'app_activity')]
    public function activity(string $slug, ActivityRepository $activityRepository): Response
    {        
        // Trouver l'activité' par son slug
        $activity = $activityRepository->findOneBySlug($slug);

        // Si l'activité n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$activity) {
            $this->addFlash('error', 'Activité introuvable.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'activity' => $activity,
        ]);
    }
}