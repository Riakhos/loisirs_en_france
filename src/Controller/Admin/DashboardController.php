<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Trend;
use App\Entity\Partner;
use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Exclusive;
use App\Entity\Eventstrend;
use App\Entity\Subcategory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Controller\Admin\UserCrudController;
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

        // Option 1. You can make your dashboard redirect to some common page of your backend
        
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
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
        yield MenuItem::subMenu('Gestion et Accès', 'fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class),
            MenuItem::linkToCrud('Partenaires', 'fas fa-list', Partner::class),
        ]);
        
        // 📌 Loisirs et sous-sections
        yield MenuItem::subMenu('Loisirs', 'fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class),
            MenuItem::linkToCrud('Sous-Catégories', 'fas fa-list', Subcategory::class),
            MenuItem::linkToCrud('Activités', 'fas fa-list', Activity::class),
        ]);

        // 📌 Évènements Tendances et sous-sections
        yield MenuItem::subMenu('Tendances', 'fas fa-star')->setSubItems([
            MenuItem::linkToCrud('Évènements Tendances', 'fas fa-list', Eventstrend::class),
            MenuItem::linkToCrud('Activités Tendances', 'fas fa-fire', Trend::class),
            MenuItem::linkToCrud('Évènements Spéciaux', 'fas fa-calendar', Event::class),
            MenuItem::linkToCrud('Offres Exclusives', 'fas fa-gift', Exclusive::class),
            MenuItem::linkToCrud('Offres Spéciales', 'fas fa-tags', Offer::class),
        ]);
    }
}