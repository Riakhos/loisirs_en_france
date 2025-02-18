<?php

namespace App\Controller\Account;

use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{
    #[Route('/compte/profil', name: 'app_account_profil')]
    public function profil(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion de l'upload de l'image
            $file = $form->get('attachment')->getData();
            
            if ($file instanceof UploadedFile) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move($this->getParameter('profil_pictures_directory'), $newFilename);
                    /** @var \App\Entity\User $user */
                    $user = $this->getUser();
                    $user->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'error',
                        'Erreur lors du téléchargement de l\'image.'
                    );
                }
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Profil mis à jour avec succès.'
            );
            return $this->redirectToRoute('app_account_profil');
        }
        
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'Votre Profil',
            'profilForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}