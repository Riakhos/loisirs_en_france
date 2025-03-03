<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Article;
use App\Form\RatingType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ArticleController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(Request $request, EntityManagerInterface $em, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Récupération des informations pour la pagination
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        // Compter le nombre total d'articles
        $totalArticles = $articleRepository->count([]);

        // Calculer le nombre total de pages
        $totalPages = ceil($totalArticles / $limit);

        // Récupérer les articles pour la page actuelle
        $articles = $articleRepository->findBy(
            [],
            ['createdAt' => 'DESC'], // Trier par date décroissante
            $limit, 
            ($page - 1) * $limit // Offset
        );

        // Création du formulaire d'ajout d'article
        $article = new Article();
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());

            // Associer l'article à l'utilisateur connecté
            $article->setBlogueur($user);
    
            /** @var UploadedFile $imageFile */
            $imageFile = $articleForm->get('image')->getData();

            if ($imageFile) {
                $uploadDir = $this->getParameter('photo_dir');
                $newFilename = sprintf('%s-%s-%s-%s.webp',date('Y'),date('m'),date('d'),sha1(uniqid(mt_rand(), true)));

                // Chemin temporaire de l'image uploadée
                $originalPath = $imageFile->getPathname();
                $webpPath = $uploadDir . '/' . $newFilename;

                // Convertir l'image en WebP
                $this->convertToWebP($originalPath, $webpPath);

                // Sauvegarder le chemin de l’image
                $article->setImage($newFilename);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash(
                'success', 
                'Article publié avec succès !'
            );

            return $this->redirectToRoute('app_blog_detail', ['id_article' => $article->getId()]);
        }
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'Quoi de neuf!',
            'articles' => $articles,
            'articleForm' => $articleForm->createView(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    
    #[Route('/blog/{id_article}', name: 'app_blog_detail')]
    public function show(int $id_article, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $em ): Response
    {
        $article = $articleRepository->find($id_article);
    
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }
    
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Création d'un nouveau rating
        $rating = new Rating();
        $ratingForm = $this->createForm(RatingType::class, $rating);
        $ratingForm->handleRequest($request);

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $rating->setUser($user);
            $rating->setArticle($article);

            $em->persist($rating);
            $em->flush();

            $this->addFlash(
                'success', 
                'Votre note a été ajoutée avec succès !'
            );

            return $this->redirectToRoute('app_blog_detail', ['id_article' => $article->getId()]);

        }
        return $this->render('blog/show.html.twig', [
            'controller_name' => $article->getTitle(),
            'article' => $article,
            'ratingForm' => $ratingForm->createView()
        ]);
    }

    private function convertToWebP(string $originalPath, string $webpPath): void
    {
        $image = imagecreatefromstring(file_get_contents($originalPath));

        if ($image !== false) {
            imagewebp($image, $webpPath, 80); // Compression 80% (modifiable)
            imagedestroy($image);
        }
    }
}