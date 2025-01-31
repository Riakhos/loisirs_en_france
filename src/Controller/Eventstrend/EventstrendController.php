<?php

namespace App\Controller\Eventstrend;

use App\Repository\EventstrendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventstrendController extends AbstractController
{
    #[Route('/evenements-tendances/{slug}', name: 'app_eventstrend')]
    public function index(string $slug, EventstrendRepository $eventstrendRepository): Response
    {
        $eventstrend = $eventstrendRepository->findOneBySlug($slug);
        
        // Si l'évènement tendance'n'existe pas, redirigez vers la page d'accueil ou affichez une erreur
        if (!$eventstrend) {
            $this->addFlash('error', "L'évènement tendance demandée n'existe pas."); // Message flash
            return $this->redirectToRoute('app_home'); // Redirection vers la page d'accueil
        }
        
        return $this->render('eventstrend/eventstrend.html.twig', [
            'controller_name' => 'Évènements Tendances',
            'eventstrend' => $eventstrend,
        ]);
    }
}