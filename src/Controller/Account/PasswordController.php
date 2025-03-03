<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Form\ForgetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordController extends AbstractController
{
	/**
	 * password
	 *
	 * @param Request $request
	 * @param UserPasswordHasherInterface $userPasswordHasherInterface
	 * @param EntityManagerInterface $entityManagerInterface
	 * @return Response
	 */
	#[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
	public function password(Request $request, UserPasswordHasherInterface $uphi, EntityManagerInterface $em): Response
	{
		$user = $this->getUser();
		$form = $this->createForm(PasswordUserType::class, $user, [
			'passwordHasher' => $uphi 
		]);
		
		$form->handleRequest(($request));

		// Si le formulaire est soumis alors :
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$this->addFlash(
				'success',
				'Votre mot de passe est correctement mis à jour.'
			);
			return $this->redirectToRoute('app_login');
		}
		
		return $this->render('account/password.html.twig', [
			'controller_name' => 'Modifier votre mot de passe',
			'modifyPwd' => $form->createView()
		]);
	}

	#[Route('/mot-de-passe-oublie', name: 'app_forgotten_password')]
	public function forgottenPassword(Request $request, EntityManagerInterface $em, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository): Response
	{
		$forgetForm = $this->createForm(ForgetPasswordFormType::class);
		$forgetForm->handleRequest($request);
	
		if ($forgetForm->isSubmitted() && $forgetForm->isValid()) {
			$data = $forgetForm->getData();
			$user = $userRepository->findOneBy(['email' => $data['email']]);
			
			if ($user) {
				// Générer un token
				$token = $tokenGenerator->generateToken();
				$user->setResetToken($token);
				$em->flush();
	
				// Envoyer l'email
				$email = (new TemplatedEmail())
					->from('noreply@loisirsenfrance.com')
					->to($user->getEmail())
					->subject('Réinitialisation de votre mot de passe')
					->htmlTemplate('emails/reset_password.html.twig')
					->context(['token' => $token]);
	
				$mailer->send($email);
	
				$this->addFlash(
					'success', 
					'Un email vous a été envoyé.'
				);
				
				return $this->redirectToRoute('app_login');
				
			} else {
				$this->addFlash(
					'danger', 
					'Aucun compte trouvé pour cet email.'
				);

				return $this->redirectToRoute('app_forgotten_password');
				
			}
		}

		return $this->render('security/forgotten_password.html.twig', [
			'controller_name' => 'Mot de passe oublié',
			'forgetForm' => $forgetForm->createView(),
		]);
	}

	#[Route('/reinitialiser-mot-de-passe/{token}', name: 'app_reset_password')]
	public function resetPassword(string $token, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, UserRepository $userRepository): Response
	{
		$user = $userRepository->findOneBy(['resetToken' => $token]);

		if (!$user) {
			$this->addFlash(
				'danger', 
				'Lien invalide ou expiré.'
			);
			return $this->redirectToRoute('app_forgotten_password');
		}


		$resetForm = $this->createForm(ResetPasswordFormType::class);
		$resetForm->handleRequest($request);

		if ($resetForm->isSubmitted() && $resetForm->isValid()) {
			$plainPassword = $resetForm->get('plainPassword')->getData();

			if ($plainPassword) {
				$user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
				$user->setResetToken(null);
				$em->flush();

				$this->addFlash(
					'success', 
					'Votre mot de passe a été réinitialisé.'
				);
				return $this->redirectToRoute('app_login');
			} else {
				$this->addFlash(
					'danger', 
					'Veuillez entrer un mot de passe valide.'
				);
				return $this->redirectToRoute('app_reset_password', ['token' => $token]);
			}
		}

		return $this->render('security/reset_password.html.twig', [
			'controller_name' => 'Réinitialisation du mot de passe',
			'resetForm' => $resetForm->createView(),
		]);
	}
}