<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Trend;
use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Exclusive;
use App\Entity\Eventstrend;
use App\Entity\Subcategory;
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
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Sous-Catégories', 'fas fa-list', Subcategory::class);
        yield MenuItem::linkToCrud('Activités', 'fas fa-list', Activity::class);
        yield MenuItem::linkToCrud('Eventstrends', 'fas fa-list', Eventstrend::class);
        yield MenuItem::linkToCrud('Trends', 'fas fa-list', Trend::class);
        yield MenuItem::linkToCrud('Events', 'fas fa-list', Event::class);
        yield MenuItem::linkToCrud('Exclusives', 'fas fa-list', Exclusive::class);
        yield MenuItem::linkToCrud('Offers', 'fas fa-list', Offer::class);
    }
}