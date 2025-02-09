<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Partner;
use App\Form\PartnerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PartnerController extends AbstractController
{
    #[Route('/partner', name: 'app_partner')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $partner = new Partner();
        $partner->setUser($user);

        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe si un champ password est présent
            if ($user->getPassword()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->persist($partner);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre compte est correctement créé, veuillez-vous connecter.'
            );
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('modals/partner_modal.html.twig', [
            'partnerForm' => $form->createView(),
        ]);
    }
}