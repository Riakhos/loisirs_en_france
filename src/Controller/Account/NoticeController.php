<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController extends AbstractController
{	
    #[Route('/compte/avis', name: 'app_account_notice')]
    public function notice(): Response
    {
        return $this->render('account/notice.html.twig', [
            'controller_name' => 'NoticeController',
        ]);
    }
}