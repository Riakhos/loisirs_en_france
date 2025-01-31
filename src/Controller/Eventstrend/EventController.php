<?php

namespace App\Controller\Eventstrend;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('evenements-speciaux/{slug}', name: 'app_event')]
    public function event(string $slug, EventRepository $eventRepository ): Response
    {
        // Trouver l'évènement spéciale par son slug
        $event = $eventRepository->findOneBySlug($slug);

        // Si l'évènement spéciale n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$event) {
            $this->addFlash('error', 'Évènement spéciale introuvable.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('eventstrend/event.html.twig', [
            'controller_name' => 'Évènements Spéciaux',
            'event' => $event,
        ]);
    }
}