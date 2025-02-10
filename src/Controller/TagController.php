<?php

namespace App\Controller;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TagController extends AbstractController
{
    #[Route('/tags/activite/{slug}', name: 'app_tags')]
    public function viewTags(string $slug, EntityManagerInterface $entityManagerInterface): Response
    {
        $activity = $entityManagerInterface->getRepository(Activity::class)->findOneBy(['slug' => $slug]);

        if (!$activity) {
            throw $this->createNotFoundException('L\'activité n\'a pas été trouvée.');
        }

        return $this->render('components/tags.html.twig', [
            'controller_name' => 'Tags',
            'activity' => $activity,
            'tags' => $activity->getTags(), // Charger uniquement les tags
        ]);
    }
}