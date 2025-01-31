<?php

namespace App\Controller\Eventstrend;

use App\Repository\ExclusiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExclusiveController extends AbstractController
{
    #[Route('offres-exclusives/{slug}', name: 'app_exclusive')]
    public function exclusive(string $slug, ExclusiveRepository $exclusiveRepository): Response
    {
        // Trouver l'offre exclusive par son slug
        $exclusive = $exclusiveRepository->findOneBySlug($slug);

        // Si l'offre exclusive n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$exclusive) {
            $this->addFlash('error', 'Offre exclusive introuvable.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('eventstrend/exclusive.html.twig', [
            'controller_name' => 'Offres Exclusives',
            'exclusive' => $exclusive,
        ]);
    }
}