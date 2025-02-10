<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Partner;
use App\Form\RatingType;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PartnerController extends AbstractController
{
    #[Route('/partenaire', name: 'app_partner_list')]
    public function list(PartnerRepository $partnerRepository): Response
    {
        $partners = $partnerRepository->findAll();

        return $this->render('account/list.html.twig', [
            'controller_name' => 'Tous nos partenaires',
            'partners' => $partners,
        ]);
    }

    #[Route('/partenaire/{slug}', name: 'app_partner')]
    public function partner(string $slug, PartnerRepository $partnerRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Trouver le partenaire par son slug
        $partner = $partnerRepository->findOneBySlug($slug);

        // Si le partenaire n'est pas trouvée, rediriger vers la page d'accueil avec un message d'erreur
        if (!$partner) {
            $this->addFlash(
                'error',
                'Partenaire introuvable.'
            );
            return $this->redirectToRoute('app_account');
        }
        
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour ajouter une note.'
            );
            return $this->redirectToRoute('app_login');
        }

        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setPartner($partner);
            
            $em->persist($rating);
            $em->flush();
            
            $this->addFlash(
                'success',
                'Votre note a été ajoutée avec succès.'
            );
            
            return $this->redirectToRoute('app_partner', [
                'slug' => $partner->getSlug()
            ]);
        }
        
        return $this->render('account/partner.html.twig', [
            'controller_name' =>  $partner->getSlug(),
            'partner' => $partner,
            'ratings' => $partner->getRatings(),
            'averageRating' => $partner->getAverageRating(),
            'ratingForm' => $form->createView(),
        ]);
    }
    
    #[Route('/partenaire/inscription', name: 'app_partner_register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifier si l'utilisateur est déjà connecté
        $currentUser = $this->getUser();
        
        $partner = new Partner();

        $form = $this->createForm(PartnerType::class, $partner, [
            'is_authenticated' => (bool) $currentUser, // Option personnalisée pour Twig
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Si l'utilisateur est connecté, on l'associe directement au partenaire
            if ($currentUser) {
                $partner->setUser($currentUser);
            } else {
                
                // Sinon, on récupère les données du formulaire pour créer un nouvel utilisateur
                $email = $form->get('user_email')->getData();
                $plainPassword = $form->get('user_password')->getData();
                
                // Vérifier si l'utilisateur existe déjà
                $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                
                if ($existingUser) {
                    $this->addFlash(
                        'error',
                        'Un compte avec cet email existe déjà. Veuillez vous connecter.'
                    );
                    return $this->redirectToRoute('app_login');
                }

                // Création d'un nouvel utilisateur
                $user = new User();
                $user->setEmail($email);
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);

                // Associer l'utilisateur au partenaire
                $partner->setUser($user);

                // Sauvegarde en base
                $em->persist($user);
            }

            // Sauvegarde du partenaire
            $em->persist($partner);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre partenariat a été enregistré avec succès.'
            );
            return $this->redirectToRoute('app_account');
        }

        return $this->render('modals/partner_modal.html.twig', [
            'partnerForm' => $form->createView(),
            'last_username' => $authenticationUtils->getLastUsername(), // Pré-remplissage email en cas d'échec de connexion
            'error' => $authenticationUtils->getLastAuthenticationError(), // Message d'erreur si connexion échouée
            'is_authenticated' => (bool) $currentUser, // Pour adapter l'affichage du formulaire
        ]);
    }
}