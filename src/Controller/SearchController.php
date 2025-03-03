<?php

namespace App\Controller;

use App\Form\SearchAllType;
use App\Repository\EventRepository;
use App\Repository\OfferRepository;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(
        Request $request,
        OfferRepository $offerRepository,
        EventRepository $eventRepository,
        ActivityRepository $activityRepository,
    ): Response
    {
        // Création du formulaire
        $searchForm = $this->createForm(SearchAllType::class);
        $searchForm->handleRequest($request);

        // Récupération des résultats filtrés ou de tous les résultats par défaut
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            
            $events = $eventRepository->searchByCriteria($data);
            $offers = $offerRepository->searchByCriteria($data);
            $activities = $activityRepository->searchByCriteria($data);
        } else {
            $events = $eventRepository->findBy([], ['createdAt' => 'DESC']);
            $offers = $offerRepository->findBy([], ['createdAt' => 'DESC']);
            $activities = $activityRepository->findBy([], ['createdAt' => 'DESC']);
        }

        return $this->render('home/search.html.twig', [
            'controller_name' => 'Liste des loisirs',
            'searchForm' => $searchForm->createView(),
            'events' => $events,
            'offers' => $offers,
            'activities' => $activities
        ]);
    }
}