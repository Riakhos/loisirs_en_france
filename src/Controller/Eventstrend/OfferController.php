<?php

namespace App\Controller\Eventstrend;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    #[Route('offres-speciales/{slug}', name: 'app_offer')]
    public function offer(string $slug, OfferRepository $offerRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Trouver l'offre spéciale par son slug
        $offer = $offerRepository->findOneBySlug($slug);

        // Si l'offre spéciale n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$offer) {
            $this->addFlash(
                'error',
                'Offre spéciale introuvable.'
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
            $rating->setOffer($offer);
            
            $em->persist($rating);
            $em->flush();
            
            $this->addFlash(
                'success',
                'Votre note a été ajoutée avec succès.'
            );
            
            return $this->redirectToRoute('app_offer', [
                'slug' => $offer->getSlug()
            ]);
        }
        
        return $this->render('eventstrend/offer.html.twig', [
            'controller_name' => 'Offres Spéciales',
            'offer' => $offer,
            'ratings' => $offer->getRatings(),
            'averageRating' => $offer->getAverageRating(),
            'ratingForm' => $form->createView(),
        ]);
    }
}