<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Trend;
use App\Entity\Rating;
use App\Entity\Partner;
use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Exclusive;
use App\Entity\Eventstrend;
use App\Entity\Subcategory;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. Vous pouvez faire en sorte que votre tableau de bord soit redirigé vers une page commune de votre backend
        
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. Vous pouvez faire en sorte que votre tableau de bord redirige vers différentes pages en fonction de l'utilisateur
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. Vous pouvez créer un modèle personnalisé pour afficher un tableau de bord approprié avec des widgets, etc.
        // (astuce : il est plus facile d'utiliser un modèle qui s'étend à partir de @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Loisirs En France');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // 📌 Espace Membres et Partenaires
        yield MenuItem::subMenu('Utilisateur', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Partenaires', 'fas fa-handshake', Partner::class),
        ]);

        // 📌 Loisirs et sous-sections
        yield MenuItem::subMenu('Loisirs', 'fas fa-dice')->setSubItems([
            MenuItem::linkToCrud('Catégories', 'fas fa-layer-group', Category::class),
            MenuItem::linkToCrud('Sous-Catégories', 'fas fa-stream', Subcategory::class),
            MenuItem::linkToCrud('Activités', 'fas fa-running', Activity::class),
        ]);

        // 📌 Évènements Tendances et sous-sections
        yield MenuItem::subMenu('Tendances', 'fas fa-fire')->setSubItems([
            MenuItem::linkToCrud('Évènements Tendances', 'fas fa-bolt', Eventstrend::class),
            MenuItem::linkToCrud('Activités Tendances', 'fas fa-chart-line', Trend::class),
            MenuItem::linkToCrud('Évènements Spéciaux', 'fas fa-calendar-alt', Event::class),
            MenuItem::linkToCrud('Offres Exclusives', 'fas fa-gift', Exclusive::class),
            MenuItem::linkToCrud('Offres Spéciales', 'fas fa-tag', Offer::class),
        ]);

        // 📌 Évaluations & Mots-clés
        yield MenuItem::subMenu('Critères & Avis', 'fas fa-star')->setSubItems([
            MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class),
            MenuItem::linkToCrud('Notes et avis', 'fas fa-star-half-alt', Rating::class),
        ]);
    }

}