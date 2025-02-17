<?php

namespace App\Controller\Eventstrend;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('evenements-speciaux/{slug}', name: 'app_event')]
    public function event(string $slug, EventRepository $eventRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Trouver l'évènement spéciale par son slug
        $event = $eventRepository->findOneBySlug($slug);

        // Si l'évènement spéciale n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$event) {
            $this->addFlash('error', 'Évènement spéciale introuvable.');
            return $this->redirectToRoute('app_home');
        }
        

        // Vérifier si l'utilisateur est connecté
        $ratingForm = null;
        $user = $this->getUser();
        
        if ($user) {
            $rating = new Rating();
            $ratingForm = $this->createForm(RatingType::class, $rating);
            $ratingForm->handleRequest($request);
            
            if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
                $rating->setEvent($event);
                $rating->setUser($user); // Assurez-vous d'enregistrer l'auteur de la note
                $em->persist($rating);
                $em->flush();
                
                $this->addFlash(
                    'success',
                    'Votre note a été ajoutée avec succès.'
                );
                
                return $this->redirectToRoute('app_event', [
                    'slug' => $event->getSlug()
                ]);
            }
        }
        
        return $this->render('eventstrend/event.html.twig', [
            'controller_name' => 'Évènements Spéciaux',
            'event' => $event,
            'ratings' => $event->getRatings(),
            'averageRating' => $event->getAverageRating(),
            'ratingForm' => $ratingForm ? $ratingForm->createView() : null,
        ]);
    }
}