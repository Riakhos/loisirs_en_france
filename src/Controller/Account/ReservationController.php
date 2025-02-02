<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
	#[Route('/compte/reservation', name: 'app_account_reservation')]
    public function reservation(): Response
    {
        return $this->render('account/reservation.html.twig', [
            'controller_name' => 'Vos Réservations ',
        ]);
    }
}