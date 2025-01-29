<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
	#[Route('/compte/profil', name: 'app_account_profil')]
    public function profil(): Response
    {
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}