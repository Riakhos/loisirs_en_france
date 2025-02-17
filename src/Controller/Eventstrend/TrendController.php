<?php

namespace App\Controller\Eventstrend;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\TrendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrendController extends AbstractController
{
    #[Route('/activites-tendances/{slug}', name: 'app_trend')]
    public function trend(string $slug, TrendRepository $trendRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Trouver l'activité tendance par son slug
        $trend = $trendRepository->findOneBySlug($slug);

        // Si l'activité tendance n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$trend) {
            $this->addFlash(
                'error',
                'Activité tendance introuvable.'
            );
            return $this->redirectToRoute('app_home');
        }

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        
        // Créer un tableau pour stocker les formulaires de notation
        $ratingForms = [];
        
        if (!$user) {
            foreach ($trend->getActivities() as $activity) {
                // Créer un formulaire de notation pour chaque activité
                $rating = new Rating();
                // Lier la note à l'activité correspondante
                $rating->setActivity($activity);
                $rating->setUser($user);
                
                $form = $this->createForm(RatingType::class, $rating);
                $form->handleRequest($request);
    
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($rating);
                    $em->flush();
    
                    $this->addFlash(
                        'success',
                        'Votre note a été ajoutée avec succès.'
                    );
                    
                    // Rediriger vers la même page pour éviter un double envoi du formulaire
                    return $this->redirectToRoute('app_trend', [
                        'slug' => $trend->getSlug()
                    ]);
                }
    
                // Ajouter le formulaire de chaque activité au tableau
                $ratingForms[$activity->getId()] = $form->createView();
            }
        }
        
        return $this->render('eventstrend/trend.html.twig', [
            'controller_name' => 'Activités Tendances',
            'trend' => $trend,
            'activities' => $trend->getActivities(),
            'ratingForms' => $ratingForms,
        ]);
    }
}