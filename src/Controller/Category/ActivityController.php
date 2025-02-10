<?php

namespace App\Controller\Category;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActivityController extends AbstractController
{
    #[Route('/activite/{slug}', name: 'app_activity')]
    public function activity(string $slug, ActivityRepository $activityRepository, EntityManagerInterface $em, Request $request): Response
    {        
        // Trouver l'activité' par son slug
        $activity = $activityRepository->findOneBySlug($slug);

        // Si l'activité n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$activity) {
            $this->addFlash(
                'error', 
                'Activité introuvable.'
            );
            return $this->redirectToRoute('app_home');
        }

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour ajouter une note.'
            );
            return $this->redirectToRoute('app_login');
        }

        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setActivity($activity);
            
            $em->persist($rating);
            $em->flush();
            
            $this->addFlash(
                'success',
                'Votre note a été ajoutée avec succès.'
            );
            
            return $this->redirectToRoute('app_activity', [
                'slug' => $activity->getSlug()
            ]);
        }
        
        return $this->render('category/activity.html.twig', [
            'controller_name' => 'Activité',
            'activity' => $activity,
            'ratings' => $activity->getRatings(),
            'averageRating' => $activity->getAverageRating(),
            'ratingForm' => $form->createView(),
        ]);
    }
}