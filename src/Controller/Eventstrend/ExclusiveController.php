<?php

namespace App\Controller\Eventstrend;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\ExclusiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExclusiveController extends AbstractController
{
    #[Route('offres-exclusives/{slug}', name: 'app_exclusive')]
    public function exclusive(string $slug, ExclusiveRepository $exclusiveRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Trouver l'offre exclusive par son slug
        $exclusive = $exclusiveRepository->findOneBySlug($slug);

        // Si l'offre exclusive n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$exclusive) {
            $this->addFlash(
                'error',
                'Offre exclusive introuvable.'
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
        
        // Créer un tableau pour stocker les formulaires de notation
        $ratingForms = [];
        
        foreach ($exclusive->getActivities() as $activity) {
            // Créer un formulaire de notation pour chaque activité
            $rating = new Rating();
            $form = $this->createForm(RatingType::class, $rating);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Lier la note à l'activité correspondante
                $rating->setActivity($activity);
                
                $em->persist($rating);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Votre note a été ajoutée avec succès.'
                );
                
                // Rediriger vers la même page pour éviter un double envoi du formulaire
                return $this->redirectToRoute('app_exclusive', [
                    'slug' => $exclusive->getSlug()
                ]);
            }

            // Ajouter le formulaire de chaque activité au tableau
            $ratingForms[$activity->getId()] = $form->createView();
        }
        return $this->render('eventstrend/exclusive.html.twig', [
            'controller_name' => 'Offres Exclusives',
            'exclusive' => $exclusive,
            'ratingForms' => $ratingForms,
        ]);
    }
}