<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExplorenowController extends AbstractController
{
    #[Route('/explorez-maintenant', name: 'app_explorenow')]
    public function index(): Response
    {
        return $this->render('home/explorenow.html.twig', [
            'controller_name' => 'Explorez Maintenant',
        ]);
    }
}