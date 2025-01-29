<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/compte/message', name: 'app_account_message')]
    public function message(): Response
    {
        return $this->render('account/message.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }    
}