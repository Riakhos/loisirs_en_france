<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/reservation', name: 'app_account_reservation')]
    public function reservation(): Response
    {
        return $this->render('account/reservation.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/profil', name: 'app_account_profil')]
    public function profil(): Response
    {
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/message', name: 'app_account_message')]
    public function message(): Response
    {
        return $this->render('account/message.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/avis', name: 'app_account_notice')]
    public function notice(): Response
    {
        return $this->render('account/avis.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/panier', name: 'app_account_basket')]
    public function basket(): Response
    {
        return $this->render('account/panier.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $userPasswordHasherInterface 
        ]);
        
        $form->handleRequest(($request));

        // Si le formulaire est soumis alors :
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe est correctement mis à jour.'
            );
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('account/password.html.twig', [
            'controller_name' => 'AccountController',
            'modifyPwd' => $form->createView()
        ]);
    }
}