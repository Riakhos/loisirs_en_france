<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        $form = $this->createForm(RegisterUserType::class);
        
        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController',
            'registerForm' => $form->createView()
        ]);
    }
}