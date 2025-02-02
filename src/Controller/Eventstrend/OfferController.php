<?php

namespace App\Controller\Eventstrend;

use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    #[Route('offres-speciales/{slug}', name: 'app_offer')]
    public function offer(string $slug, OfferRepository $offerRepository): Response
    {
        // Trouver l'offre spéciale par son slug
        $offer = $offerRepository->findOneBySlug($slug);

        // Si l'offre spéciale n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$offer) {
            $this->addFlash('error', 'Offre spéciale introuvable.');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('eventstrend/offer.html.twig', [
            'controller_name' => 'Offres Spéciales',
            'offer' => $offer,
        ]);
    }
}