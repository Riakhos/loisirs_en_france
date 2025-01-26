<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CardController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_card')]
    public function card(): Response
    {
        return $this->render('card/card.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }
}