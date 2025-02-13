<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
}