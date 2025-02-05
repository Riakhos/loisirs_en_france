<?php

namespace App\EventListener;

use App\Service\ImageOptimizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class GenericImageListener implements EventSubscriberInterface
{
    private $imageOptimizer;
    private $entityManager;

    public function __construct(ImageOptimizer $imageOptimizer, EntityManagerInterface $entityManager)
    {
        $this->imageOptimizer = $imageOptimizer;
        $this->entityManager = $entityManager;
    }

    /**
     * Retourne un tableau des événements auxquels ce listener s'abonne.
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'onPrePersist',   // Événement avant persist
            BeforeEntityUpdatedEvent::class => 'onPreUpdate',       // Événement avant mise à jour
        ];
    }

    /**
     * Optimisation de l'image avant la persistance de l'entité.
     *
     * @param BeforeEntityPersistedEvent $event
     */
    public function onPrePersist(BeforeEntityPersistedEvent $event)
    {
        $this->optimizeImages($event->getEntityInstance());
    }

    /**
     * Optimisation de l'image avant la mise à jour de l'entité.
     *
     * @param BeforeEntityUpdatedEvent $event
     */
    public function onPreUpdate(BeforeEntityUpdatedEvent $event)
    {
        $this->optimizeImages($event->getEntityInstance());
    }

    /**
     * Optimisation des images dans les entités.
     *
     * @param object $entity
     */
    private function optimizeImages($entity): void
    {
        if (!$entity) {
            return;
        }
    
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();
    
        foreach ($properties as $property) {
            if (strpos($property->getName(), 'image') !== false) {
                $this->processImageField($entity, $property);
            }
        }
    
        $this->entityManager->persist($entity);
    }

    private function processImageField($entity, \ReflectionProperty $property): void
    {
        $getter = 'get' . ucfirst($property->getName());
        $setter = 'set' . ucfirst($property->getName());

        if (!method_exists($entity, $getter) || !method_exists($entity, $setter)) {
            return;
        }

        $imageFile = $entity->$getter();

        if ($imageFile instanceof UploadedFile) {
            $filePath = $imageFile->getPathname();

            if (!file_exists($filePath) || !is_readable($filePath)) {
                error_log("Fichier inexistant ou illisible : " . $filePath);
                return;
            }

            try {
                $this->imageOptimizer->resize($filePath);
                $webpFilename = $this->imageOptimizer->convertToWebP($filePath);

                if ($webpFilename) {
                    error_log("Image convertie en WebP : " . $webpFilename);
                    $entity->$setter(basename($webpFilename));
                } else {
                    error_log("La conversion WebP a échoué pour " . $filePath);
                }
            } catch (\Exception $e) {
                error_log("Erreur lors du traitement de l'image : " . $e->getMessage());
            }
        }
    }
}